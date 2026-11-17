<?php

namespace App\Services\Fulfillment;

use App\Models\Order;
use App\Models\VaultItem;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class VaultDeliveryService
{
    /**
     * Deliver digital codes/credentials from vault inventory to order.
     *
     * @throws Exception
     */
    public function deliver(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            /** @var Collection<int, VaultItem> $availableItems */
            $availableItems = VaultItem::query()
                ->where('product_id', $order->product_id)
                ->where('status', 'available')
                ->lockForUpdate()
                ->limit($order->quantity)
                ->get();

            if ($availableItems->count() < $order->quantity) {
                throw new Exception(
                    "المخزون المتوفر غير كافٍ لتسليم الطلب #{$order->order_number}. المطلوب: {$order->quantity}، المتوفر: {$availableItems->count()}."
                );
            }

            $collectedCredentials = [];

            foreach ($availableItems as $item) {
                $item->status = 'sold';
                $item->order_id = $order->id;
                $item->save();

                $collectedCredentials[] = $item->credentials;
            }

            $plainCredentials = implode("\n", $collectedCredentials);

            $order->delivery_data = Crypt::encryptString($plainCredentials);
            $order->status = 'completed';
            $order->save();

            return $order;
        });
    }
}
