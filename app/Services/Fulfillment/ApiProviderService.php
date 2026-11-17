<?php

namespace App\Services\Fulfillment;

use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ApiProviderService
{
    /**
     * Dispatch an order to external SMM/SMS API Provider.
     *
     * @throws Exception
     */
    public function dispatchOrder(Order $order): Order
    {
        $order->loadMissing(['product.apiProvider', 'user']);
        $product = $order->product;
        $provider = $product?->apiProvider;

        if (! $provider || ! $provider->is_active || empty($provider->api_url)) {
            throw new Exception("لا يوجد مزود API نشط مرتبط بالمنتج #{$product?->name}.");
        }

        if (empty($product->external_service_id)) {
            throw new Exception("معرف الخدمة الخارجي (external_service_id) غير محدد للمنتج #{$product->name}.");
        }

        $payload = [
            'key' => $provider->api_key,
            'action' => 'add',
            'service' => $product->external_service_id,
            'link' => $order->target_input ?? '',
            'quantity' => $order->quantity,
        ];

        $response = Http::asForm()->timeout(15)->post($provider->api_url, $payload);

        if (! $response->successful()) {
            Log::error("API Provider Dispatch Failed for Order #{$order->order_number}: ".$response->body());
            throw new Exception('فشل الاتصال بمزود الـ API: '.$response->body());
        }

        $data = $response->json();
        if (isset($data['error'])) {
            throw new Exception("خطأ من مزود الـ API: {$data['error']}");
        }

        $externalId = (string) ($data['order'] ?? $data['order_id'] ?? $data['id'] ?? '');
        if (empty($externalId)) {
            throw new Exception('لم يرجع مزود الـ API معرف الطلب الخارجي.');
        }

        $order->external_order_id = $externalId;
        $order->status = 'processing';
        $order->save();

        return $order;
    }

    /**
     * Sync processing orders with external providers and refund failed ones.
     */
    public function syncProcessingOrders(): int
    {
        $orders = Order::query()
            ->with(['product.apiProvider', 'user'])
            ->where('status', 'processing')
            ->whereNotNull('external_order_id')
            ->get();

        $syncedCount = 0;

        foreach ($orders as $order) {
            $provider = $order->product?->apiProvider;
            if (! $provider || empty($provider->api_url)) {
                continue;
            }

            try {
                $response = Http::asForm()->timeout(10)->post($provider->api_url, [
                    'key' => $provider->api_key,
                    'action' => 'status',
                    'order' => $order->external_order_id,
                ]);

                if (! $response->successful()) {
                    continue;
                }

                $data = $response->json();
                $status = strtolower((string) ($data['status'] ?? ''));

                if (in_array($status, ['completed', 'success', 'code_received'], true) || ! empty($data['code']) || ! empty($data['sms'])) {
                    $resultText = (string) ($data['code'] ?? $data['sms'] ?? 'تم إكمال الطلب بنجاح لدى المزود الخارجي');
                    $order->delivery_data = Crypt::encryptString($resultText);
                    $order->status = 'completed';
                    $order->save();
                    $syncedCount++;
                } elseif (in_array($status, ['canceled', 'cancelled', 'refunded', 'failed', 'expired'], true)) {
                    DB::transaction(function () use ($order) {
                        $user = User::query()->lockForUpdate()->find($order->user_id);
                        if ($user) {
                            $user->balance = bcadd((string) $user->balance, (string) $order->total_price, 2);
                            $user->save();
                        }

                        $order->status = 'failed';
                        $order->save();
                    });
                    $syncedCount++;
                }
            } catch (Throwable $e) {
                Log::warning("Error syncing order #{$order->order_number}: ".$e->getMessage());
            }
        }

        return $syncedCount;
    }
}
