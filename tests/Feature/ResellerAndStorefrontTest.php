<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\VaultItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResellerAndStorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_reseller_api_requires_valid_api_key(): void
    {
        $response = $this->getJson('/api/v1/balance');
        $response->assertStatus(401);

        $responseInvalid = $this->withHeaders(['X-API-KEY' => 'invalid_key'])->getJson('/api/v1/balance');
        $responseInvalid->assertStatus(401);
    }

    public function test_reseller_api_balance_and_products_and_order_placement(): void
    {
        $reseller = User::factory()->create([
            'role' => 'reseller',
            'balance' => 50.00,
            'api_key' => 'reseller_secret_api_key_123',
        ]);

        $product = Product::create([
            'name' => 'API Netflix 4K',
            'delivery_type' => 'vault',
            'price' => 12.00,
            'reseller_price' => 8.00,
            'is_active' => true,
        ]);

        VaultItem::create([
            'product_id' => $product->id,
            'credentials' => 'user:pass:net4k',
            'status' => 'available',
        ]);

        // 1. Check Balance API
        $balanceRes = $this->withHeaders(['X-API-KEY' => 'reseller_secret_api_key_123'])
            ->getJson('/api/v1/balance');
        $balanceRes->assertStatus(200);
        $this->assertEquals(50.00, $balanceRes->json('data.balance'));

        // 2. Check Products API
        $productsRes = $this->withHeaders(['X-API-KEY' => 'reseller_secret_api_key_123'])
            ->getJson('/api/v1/products');
        $productsRes->assertStatus(200);
        $this->assertEquals(8.00, $productsRes->json('data.0.reseller_price'));

        // 3. Place Order via API
        $orderRes = $this->withHeaders(['X-API-KEY' => 'reseller_secret_api_key_123'])
            ->postJson('/api/v1/orders', [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $orderRes->assertStatus(200);
        $orderRes->assertJsonPath('success', true);
        $orderRes->assertJsonPath('data.status', 'completed');
        $orderRes->assertJsonPath('data.delivery_data', 'user:pass:net4k');

        $this->assertEquals(42.00, $reseller->fresh()->balance);
    }

    public function test_storefront_home_and_customer_registration_and_purchase(): void
    {
        $product = Product::create([
            'name' => 'Xbox Game Pass 1M',
            'delivery_type' => 'vault',
            'price' => 6.00,
            'reseller_price' => 4.50,
            'is_active' => true,
        ]);

        VaultItem::create([
            'product_id' => $product->id,
            'credentials' => 'XBOX-KEY-1122',
            'status' => 'available',
        ]);

        // 1. Visit Storefront Home
        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('Xbox Game Pass 1M');
        $homeRes->assertSee('$6.00');

        // 2. Register customer
        $registerRes = $this->post('/register', [
            'name' => 'Store Customer',
            'email' => 'client@store.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);
        $registerRes->assertRedirect(route('customer.account'));
        $this->assertAuthenticated();

        $user = auth()->user();
        $user->balance = 20.00;
        $user->save();

        // 3. Visit Account page
        $accountRes = $this->get('/account');
        $accountRes->assertStatus(200);
        $accountRes->assertSee('$20.00');

        // 4. Purchase Product
        $buyRes = $this->post("/buy/{$product->id}", [
            'quantity' => 1,
        ]);
        $buyRes->assertRedirect(route('customer.account'));

        $this->assertEquals(14.00, $user->fresh()->balance);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'status' => 'completed',
        ]);

        // Verify order code appears on account page
        $accountAfterBuy = $this->get('/account');
        $accountAfterBuy->assertSee('XBOX-KEY-1122');
    }

    public function test_reseller_panel_access_restriction(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $reseller = User::factory()->create(['role' => 'reseller']);

        $this->actingAs($customer);
        $this->get('/reseller')->assertStatus(403);

        $this->actingAs($reseller);
        $this->get('/reseller')->assertStatus(200);
    }
}
