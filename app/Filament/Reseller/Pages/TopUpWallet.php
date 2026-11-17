<?php

namespace App\Filament\Reseller\Pages;

use App\Models\Deposit;
use App\Services\Payments\BinancePayService;
use App\Services\Payments\LemonSqueezyService;
use BackedEnum;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class TopUpWallet extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'شحن المحفظة';

    protected static ?string $title = 'شحن رصيد المحفظة';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.reseller.pages.top-up-wallet';

    public float $amount = 20.00;

    public string $gateway = 'lemonsqueezy';

    public function submit(): void
    {
        $this->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'gateway' => ['required', 'in:lemonsqueezy,binance'],
        ], [
            'amount.required' => 'يرجى إدخال المبلغ المراد شحنه.',
            'amount.min' => 'الحد الأدنى للشحن هو $1.',
        ]);

        try {
            $deposit = Deposit::create([
                'trx' => 'DEP-'.strtoupper(bin2hex(random_bytes(5))),
                'user_id' => auth()->id(),
                'amount' => $this->amount,
                'gateway' => $this->gateway,
                'status' => 'pending',
            ]);

            if ($this->gateway === 'lemonsqueezy') {
                $checkoutUrl = app(LemonSqueezyService::class)->createCheckout($deposit);
            } else {
                $checkoutUrl = app(BinancePayService::class)->createOrder($deposit);
            }

            $this->redirect($checkoutUrl);
        } catch (Exception $e) {
            Notification::make()
                ->title('خطأ في معالجة الدفع')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
