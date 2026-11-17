<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Fulfillment\OrderCheckoutService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResellerApiController extends Controller
{
    public function __construct(
        protected OrderCheckoutService $orderCheckoutService
    ) {}

    /**
     * Get reseller balance.
     */
    public function balance(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'balance' => (float) $user->balance,
                'currency' => 'USD',
            ],
        ]);
    }

    /**
     * List active products with reseller prices.
     */
    public function products(): JsonResponse
    {
        $products = Product::query()
            ->where('is_active', true)
            ->withCount('availableVaultItems')
            ->get()
            ->map(fn (Product $p) => [
                'id' => $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'delivery_type' => $p->delivery_type,
                'reseller_price' => (float) $p->reseller_price,
                'available_stock' => $p->isVault() ? $p->available_vault_items_count : 'unlimited',
            ]);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Place order via reseller API.
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'target_input' => ['nullable', 'string', 'max:500'],
        ]);

        $user = $request->user();
        $product = Product::query()
            ->where('id', $validated['product_id'])
            ->where('is_active', true)
            ->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج غير موجود أو غير نشط حالياً.',
            ], 404);
        }

        $quantity = (int) ($validated['quantity'] ?? 1);
        $targetInput = $validated['target_input'] ?? null;

        try {
            $order = $this->orderCheckoutService->placeOrder($user, $product, $quantity, $targetInput);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الطلب بنجاح.',
                'data' => [
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'product_name' => $product->name,
                    'quantity' => $order->quantity,
                    'total_price' => (float) $order->total_price,
                    'delivery_data' => $order->decrypted_preview,
                    'external_order_id' => $order->external_order_id,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
