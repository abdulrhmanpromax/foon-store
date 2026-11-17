<?php

namespace Tests\Feature;

use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\VaultItem;
use App\Services\Fulfillment\ApiProviderService;
use App\Services\Fulfillment\OrderCheckoutService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_and_reseller_tiered_pricing_and_balance_deduction(): void
    {
        $checkoutService = app(OrderCheckoutService::class);

        $product = Product::create([
            'name' => 'Netflix 1 Month',
            'delivery_type' => 'vault',
            'price' => 10.00,
            'reseller_price' => 7.00,
            'is_active' => true,
        ]);

        VaultItem::create([
            'product_id' => $product->id,
            'credentials' => 'user1:pass1',
            'status' => 'available',
        ]);
        VaultItem::create([
            'product_id' => $product->id,
            'credentials' => 'user2:pass2',
            'status' => 'available',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
            'balance' => 50.00,
        ]);

        $reseller = User::factory()->create([
            'role' => 'reseller',
            'balance' => 50.00,
        ]);

        // Customer purchase at regular price
        $customerOrder = $checkoutService->placeOrder($customer, $product, 1);
        $this->assertEquals(10.00, $customerOrder->total_price);
        $this->assertEquals(40.00, $customer->fresh()->balance);

        // Reseller purchase at discount price
        $resellerOrder = $checkoutService->placeOrder($reseller, $product, 1);
        $this->assertEquals(7.00, $resellerOrder->total_price);
        $this->assertEquals(43.00, $reseller->fresh()->balance);
    }

    public function test_vault_automated_delivery_and_encryption(): void
    {
        $checkoutService = app(OrderCheckoutService::class);

        $product = Product::create([
            'name' => 'Spotify Premium Code',
            'delivery_type' => 'vault',
            'price' => 5.00,
            'reseller_price' => 4.00,
            'is_active' => true,
        ]);

        $vaultItem = VaultItem::create([
            'product_id' => $product->id,
            'credentials' => 'SPOTIFY-CODE-9988-XYZ',
            'status' => 'available',
        ]);

        $user = User::factory()->create(['balance' => 20.00]);

        $order = $checkoutService->placeOrder($user, $product, 1);

        $this->assertEquals('completed', $order->status);
        $this->assertNotEmpty($order->delivery_data);
        $this->assertNotEquals('SPOTIFY-CODE-9988-XYZ', $order->delivery_data);
        $this->assertEquals('SPOTIFY-CODE-9988-XYZ', $order->decrypted_preview);

        $vaultItem->refresh();
        $this->assertEquals('sold', $vaultItem->status);
        $this->assertEquals($order->id, $vaultItem->order_id);
    }

    public function test_insufficient_vault_inventory_rolls_back_balance(): void
    {
        $checkoutService = app(OrderCheckoutService::class);

        $product = Product::create([
            'name' => 'Empty Stock Product',
            'delivery_type' => 'vault',
            'price' => 15.00,
            'reseller_price' => 12.00,
            'is_active' => true,
        ]);

        $user = User::factory()->create(['balance' => 100.00]);

        try {
            $checkoutService->placeOrder($user, $product, 2);
            $this->fail('Expected exception was not thrown.');
        } catch (Exception $e) {
            $this->assertStringContainsString('المخزون المتوفر غير كافٍ', $e->getMessage());
        }

        // Verify balance remained 100.00 (automatic rollback)
        $this->assertEquals(100.00, $user->fresh()->balance);
        $this->assertEquals(0, Order::count());
    }

    public function test_api_provider_dispatch_and_sync_flow(): void
    {
        Http::fake([
            'https://smm-panel.example.com/api/v2' => Http::sequence()
                ->push(['order' => 123456], 200)
                ->push(['status' => 'Completed'], 200),
        ]);

        $provider = ApiProvider::create([
            'name' => 'Global SMM',
            'api_url' => 'https://smm-panel.example.com/api/v2',
            'api_key' => 'secret_smm_token',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Instagram 1k Followers',
            'delivery_type' => 'api',
            'price' => 2.50,
            'reseller_price' => 2.00,
            'api_provider_id' => $provider->id,
            'external_service_id' => '101',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['balance' => 20.00]);
        $checkoutService = app(OrderCheckoutService::class);

        $order = $checkoutService->placeOrder($user, $product, 1, 'https://instagram.com/myaccount');

        $this->assertEquals('processing', $order->status);
        $this->assertEquals('123456', $order->external_order_id);
        $this->assertEquals('https://instagram.com/myaccount', $order->target_input);
        $this->assertEquals(17.50, $user->fresh()->balance);

        $apiService = app(ApiProviderService::class);
        $synced = $apiService->syncProcessingOrders();

        $this->assertEquals(1, $synced);
        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertNotEmpty($order->decrypted_preview);
    }

    public function test_api_provider_sync_refunds_balance_on_cancellation(): void
    {
        $provider = ApiProvider::create([
            'name' => 'SMS Number Provider',
            'api_url' => 'https://sms-provider.example.com/api',
            'api_key' => 'sms_token',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Telegram Virtual Number',
            'delivery_type' => 'api',
            'price' => 3.00,
            'reseller_price' => 2.50,
            'api_provider_id' => $provider->id,
            'external_service_id' => '55',
            'is_active' => true,
        ]);

        $user = User::factory()->create(['balance' => 10.00]);

        $order = Order::create([
            'order_number' => 'ORD-SYNC-FAIL',
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'total_price' => 3.00,
            'status' => 'processing',
            'external_order_id' => '778899',
        ]);

        Http::fake([
            'https://sms-provider.example.com/api' => Http::response([
                'status' => 'Canceled',
            ], 200),
        ]);

        $apiService = app(ApiProviderService::class);
        $synced = $apiService->syncProcessingOrders();

        $this->assertEquals(1, $synced);
        $order->refresh();
        $this->assertEquals('failed', $order->status);
        $this->assertEquals(13.00, $user->fresh()->balance);
    }
}
