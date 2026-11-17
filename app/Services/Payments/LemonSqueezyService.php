<?php

namespace App\Services\Payments;

use App\Models\Deposit;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LemonSqueezyService
{
    protected string $apiKey;

    protected string $storeId;

    protected string $variantId;

    protected string $webhookSecret;

    public function __construct()
    {
        $this->apiKey = (string) (config('services.lemonsqueezy.api_key') ?: env('LEMONSQUEEZY_API_KEY', ''));
        $this->storeId = (string) (config('services.lemonsqueezy.store_id') ?: env('LEMONSQUEEZY_STORE_ID', ''));
        $this->variantId = (string) (config('services.lemonsqueezy.variant_id') ?: env('LEMONSQUEEZY_VARIANT_ID', ''));
        $this->webhookSecret = (string) (config('services.lemonsqueezy.webhook_secret') ?: env('LEMONSQUEEZY_WEBHOOK_SECRET', ''));
    }

    /**
     * Create fast, stripped-down checkout session with locked custom price.
     */
    public function createCheckout(Deposit $deposit): string
    {
        $deposit->loadMissing('user');
        $customPriceInCents = (int) round((float) $deposit->amount * 100);
        $formattedAmount = number_format((float) $deposit->amount, 2);

        $payload = [
            'data' => [
                'type' => 'checkouts',
                'attributes' => [
                    'custom_price' => $customPriceInCents,
                    'product_options' => [
                        'name' => "شحن رصيد المحفظة | Wallet Top-Up (\${$formattedAmount})",
                        'description' => "فون ستور - رقم الطلب: {$deposit->trx} | Foon Store - Order: {$deposit->trx}",
                        'redirect_url' => route('customer.account'),
                        'enabled_variants' => [(int) $this->variantId],
                    ],
                    'checkout_options' => [
                        'embed' => false,
                        'media' => false,
                        'logo' => true,
                        'desc' => true,
                        'discount' => false,
                        'button_color' => '#0A0A0A',
                    ],
                    'checkout_data' => [
                        'email' => $deposit->user->email,
                        'name' => $deposit->user->name,
                        'custom' => [
                            'trx' => $deposit->trx,
                        ],
                    ],
                ],
                'relationships' => [
                    'store' => [
                        'data' => [
                            'type' => 'stores',
                            'id' => $this->storeId,
                        ],
                    ],
                    'variant' => [
                        'data' => [
                            'type' => 'variants',
                            'id' => $this->variantId,
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
            'Authorization' => 'Bearer '.$this->apiKey,
        ])->post('https://api.lemonsqueezy.com/v1/checkouts', $payload);

        if (! $response->successful()) {
            Log::error('Lemon Squeezy Create Checkout Failed: '.$response->body());
            throw new Exception('فشل إنشاء جلسة الدفع عبر Lemon Squeezy: '.$response->json('errors.0.detail', $response->body()));
        }

        $url = (string) $response->json('data.attributes.url', '');
        if ($url === '') {
            throw new Exception('لم يتم استلام رابط الدفع من Lemon Squeezy.');
        }

        return $url;
    }

    /**
     * Verify webhook signature, paid status, and exact amount before crediting user balance.
     */
    public function verifyAndProcessWebhook(string $rawBody, string $signature): bool
    {
        if ($this->webhookSecret === '' || $signature === '') {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $rawBody, $this->webhookSecret);

        if (! hash_equals($expectedSignature, $signature)) {
            Log::warning('Lemon Squeezy Webhook: Invalid signature.');

            return false;
        }

        $payload = json_decode($rawBody, true);
        if (! is_array($payload)) {
            Log::warning('Lemon Squeezy Webhook: Invalid JSON body.');

            return false;
        }

        $eventName = $payload['meta']['event_name'] ?? '';
        if ($eventName !== 'order_created') {
            return true;
        }

        $orderStatus = (string) ($payload['data']['attributes']['status'] ?? '');
        if ($orderStatus !== 'paid') {
            Log::warning("Lemon Squeezy Webhook: Order status is not paid ({$orderStatus}).");

            return false;
        }

        $trx = $payload['meta']['custom_data']['trx']
            ?? $payload['data']['attributes']['checkout_data']['custom']['trx']
            ?? null;

        if (! $trx) {
            Log::error('Lemon Squeezy Webhook: Missing TRX in custom data.');

            return false;
        }

        $gatewayOrderId = (string) ($payload['data']['id'] ?? '');
        $paidTotalCents = (int) ($payload['data']['attributes']['total'] ?? 0);

        return DB::transaction(function () use ($trx, $gatewayOrderId, $paidTotalCents) {
            $deposit = Deposit::query()
                ->where('trx', $trx)
                ->lockForUpdate()
                ->first();

            if (! $deposit) {
                Log::error("Lemon Squeezy Webhook: Deposit not found for TRX: {$trx}");

                return false;
            }

            if ($deposit->status === 'paid') {
                return true;
            }

            $expectedCents = (int) round((float) $deposit->amount * 100);
            if ($paidTotalCents > 0 && $paidTotalCents + 1 < $expectedCents) {
                Log::warning("Lemon Squeezy Webhook: Underpaid order {$trx} ({$paidTotalCents} < {$expectedCents}).");

                return false;
            }

            $deposit->status = 'paid';
            $deposit->gateway_order_id = $gatewayOrderId;
            $deposit->save();

            $user = User::query()->lockForUpdate()->find($deposit->user_id);
            if ($user) {
                $user->balance = bcadd((string) $user->balance, (string) $deposit->amount, 2);
                $user->save();
            }

            return true;
        });
    }
}
