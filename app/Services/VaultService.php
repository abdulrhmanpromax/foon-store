<?php

namespace App\Services;

use App\Models\Order;
use App\Models\VaultItem;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class VaultService
{
    /**
     * Reserve and claim vault items for an order using transaction and lock.
     *
     * @return Collection<int, VaultItem>
     */
    public function claimItems(int $productId, Order $order, int $quantity = 1): Collection
    {
        if ($quantity <= 0) {
            throw new Exception('الكمية المطلوبة يجب أن تكون أكبر من الصفر.');
        }

        return DB::transaction(function () use ($productId, $order, $quantity) {
            /** @var Collection<int, VaultItem> $availableItems */
            $availableItems = VaultItem::query()
                ->where('product_id', $productId)
                ->where('status', 'available')
                ->lockForUpdate()
                ->limit($quantity)
                ->get();

            if ($availableItems->count() < $quantity) {
                throw new Exception('المخزون المتوفر غير كافٍ لتلبية هذا الطلب.');
            }

            foreach ($availableItems as $item) {
                $item->status = 'sold';
                $item->order_id = $order->id;
                $item->save();
            }

            return $availableItems;
        });
    }
}
