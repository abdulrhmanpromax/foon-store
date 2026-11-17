<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_lemonsqueezy_webhook_credits_user_balance(): void
    {
        config(['services.lemonsqueezy.webhook_secret' => 'webhook_secret_test']);

        $user = User::factory()->create([
            'balance' => 10.00,
        ]);

        $deposit = Deposit::create([
            'trx' => 'DEP-TEST-LS',
            'user_id' => $user->id,
            'amount' => 25.00,
            'gateway' => 'lemonsqueezy',
            'status' => 'pending',
        ]);

        $payload = json_encode([
            'meta' => [
                'event_name' => 'order_created',
                'custom_data' => [
                    'trx' => $deposit->trx,
                ],
            ],
            'data' => [
                'id' => 'LS_ORD_12345',
            ],
        ]);

        $signature = hash_hmac('sha256', (string) $payload, 'webhook_secret_test');

        $response = $this->call(
            'POST',
            '/webhooks/lemonsqueezy',
            [],
            [],
            [],
            [
                'HTTP_X-Signature' => $signature,
                'CONTENT_TYPE' => 'application/json',
            ],
            $payload
        );

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        $this->assertDatabaseHas('deposits', [
            'id' => $deposit->id,
            'status' => 'paid',
            'gateway_order_id' => 'LS_ORD_12345',
        ]);

        $this->assertEquals(35.00, $user->fresh()->balance);
    }

    public function test_binance_webhook_credits_user_balance(): void
    {
        config(['services.binance.webhook_secret' => 'binance_secret_test']);

        $user = User::factory()->create([
            'balance' => 0.00,
        ]);

        $deposit = Deposit::create([
            'trx' => 'DEP-TEST-BN',
            'user_id' => $user->id,
            'amount' => 100.00,
            'gateway' => 'binance',
            'status' => 'pending',
        ]);

        $timestamp = (string) round(microtime(true) * 1000);
        $nonce = 'nonce_12345678901234567890123456';
        $payload = json_encode([
            'bizStatus' => 'PAY_SUCCESS',
            'data' => [
                'merchantTradeNo' => $deposit->trx,
                'prepayId' => 'BINANCE_TX_987',
            ],
        ]);

        $signature = strtoupper(hash_hmac('sha512', "{$timestamp}\n{$nonce}\n{$payload}\n", 'binance_secret_test'));

        $response = $this->call(
            'POST',
            '/webhooks/binance',
            [],
            [],
            [],
            [
                'HTTP_BinancePay-Signature' => $signature,
                'HTTP_BinancePay-Timestamp' => $timestamp,
                'HTTP_BinancePay-Nonce' => $nonce,
                'CONTENT_TYPE' => 'application/json',
            ],
            $payload
        );

        $response->assertStatus(200);
        $response->assertJson(['returnCode' => 'SUCCESS']);

        $this->assertDatabaseHas('deposits', [
            'id' => $deposit->id,
            'status' => 'paid',
            'gateway_order_id' => 'BINANCE_TX_987',
        ]);

        $this->assertEquals(100.00, $user->fresh()->balance);
    }
}
