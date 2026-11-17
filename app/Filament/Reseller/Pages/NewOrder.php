<?php

namespace App\Filament\Reseller\Pages;

use App\Models\Product;
use App\Services\Fulfillment\OrderCheckoutService;
use BackedEnum;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class NewOrder extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;

    protected static ?string $navigationLabel = 'طلب جديد';

    protected static ?string $title = 'إنشاء طلب جديد بسعر الجملة';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.reseller.pages.new-order';

    public ?int $productId = null;

    public int $quantity = 1;

    public ?string $targetInput = null;

    public ?string $deliveredCredentials = null;

    public function submitOrder(OrderCheckoutService $checkoutService): void
    {
        $this->validate([
            'productId' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'targetInput' => ['nullable', 'string', 'max:500'],
        ], [
            'productId.required' => 'يرجى اختيار المنتج المراد شراؤه.',
            'quantity.min' => 'الكمية يجب أن تكون 1 على الأقل.',
        ]);

        $product = Product::findOrFail($this->productId);
        $user = auth()->user();

        try {
            $order = $checkoutService->placeOrder($user, $product, $this->quantity, $this->targetInput);

            $this->deliveredCredentials = $order->decrypted_preview;

            Notification::make()
                ->title('تم تنفيذ الطلب بنجاح!')
                ->body("رقم الطلب: {$order->order_number} | الإجمالي: \${$order->total_price}")
                ->success()
                ->send();

            if (! $order->isCompleted()) {
                $this->redirect(route('filament.reseller.resources.orders.index'));
            }
        } catch (Exception $e) {
            Notification::make()
                ->title('فشل إتمام الطلب')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->where('is_active', true)
            ->withCount('availableVaultItems')
            ->orderBy('name')
            ->get();
    }

    public function getSelectedProductProperty(): ?Product
    {
        return $this->productId ? Product::find($this->productId) : null;
    }
}
