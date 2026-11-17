<?php

namespace App\Services\Fulfillment;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderCheckoutService
{
    public function __construct(
        protected VaultDeliveryService $vaultDeliveryService,
        protected ApiProviderService $apiProviderService
    ) {}

    /**
     * Place order, validate limits, calculate tiered price, deduct wallet balance atomically, and dispatch delivery.
     *
     * @throws Exception
     */
    public function placeOrder(User $user, Product $product, int $quantity = 1, ?string $targetInput = null): Order
    {
        if ($quantity <= 0) {
            throw new Exception('الكمية المطلوبة يجب أن تكون 1 على الأقل.');
        }

        if (! $product->is_active) {
            throw new Exception("المنتج {$product->name} غير متاح للطلب حالياً.");
        }

        if ($product->isApi()) {
            $minQty = max(1, (int) ($product->min_quantity ?? 1));
            $maxQty = max($minQty, (int) ($product->max_quantity ?? 1000000));

            if ($quantity < $minQty) {
                throw new Exception("الحد الأدنى للطلب في هذه الخدمة هو {$minQty}.");
            }

            if ($quantity > $maxQty) {
                throw new Exception("الحد الأقصى للطلب في هذه الخدمة هو {$maxQty}.");
            }
        }

        $totalPrice = $product->calculateTotalPrice($user, $quantity);

        return DB::transaction(function () use ($user, $product, $quantity, $targetInput, $totalPrice) {
            $lockedUser = User::query()->lockForUpdate()->findOrFail($user->id);

            if (bccomp((string) $lockedUser->balance, (string) $totalPrice, 2) < 0) {
                throw new Exception(
                    "رصيد المحفظة غير كافٍ. المبلغ المطلوب: \${$totalPrice}، الرصيد المتوفر: \${$lockedUser->balance}."
                );
            }

            $lockedUser->balance = bcsub((string) $lockedUser->balance, (string) $totalPrice, 2);
            $lockedUser->save();

            $order = Order::create([
                'order_number' => 'ORD-'.strtoupper(bin2hex(random_bytes(5))),
                'user_id' => $lockedUser->id,
                'product_id' => $product->id,
                'target_input' => $targetInput,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            if ($product->isVault()) {
                $order = $this->vaultDeliveryService->deliver($order);
            } elseif ($product->isApi()) {
                $order = $this->apiProviderService->dispatchOrder($order);
            } else {
                throw new Exception("نوع تسليم المنتج غير مدعوم: {$product->delivery_type}");
            }

            return $order;
        });
    }
}
