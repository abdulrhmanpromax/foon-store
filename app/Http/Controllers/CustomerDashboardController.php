<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Order;
use App\Models\Product;
use App\Services\Fulfillment\OrderCheckoutService;
use App\Services\Payments\BinancePayService;
use App\Services\Payments\LemonSqueezyService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function account(): View
    {
        $user = auth()->user();

        Deposit::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(BinancePayService::EXPIRY_MINUTES))
            ->update(['status' => 'failed']);

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->with('product')
            ->latest()
            ->get();

        return view('store.account', compact('user', 'orders'));
    }

    public function deposit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:10000'],
            'gateway' => ['required', 'in:lemonsqueezy,binance'],
        ]);

        $rateKey = 'create_deposit_' . auth()->id();
        if (RateLimiter::tooManyAttempts($rateKey, 6)) {
            return back()->with('error', 'تم تجاوز الحد المسموح لإنشاء طلبات الشحن. يرجى الانتظار بضع دقائق.');
        }
        RateLimiter::hit($rateKey, 900);

        try {
            if ($validated['gateway'] === 'binance') {
                Deposit::query()
                    ->where('user_id', auth()->id())
                    ->where('gateway', 'binance')
                    ->where('status', 'pending')
                    ->update(['status' => 'failed']);
            }

            $deposit = Deposit::create([
                'trx' => 'DEP-' . strtoupper(bin2hex(random_bytes(5))),
                'user_id' => auth()->id(),
                'amount' => round((float) $validated['amount'], 2),
                'gateway' => $validated['gateway'],
                'status' => 'pending',
            ]);

            if ($validated['gateway'] === 'lemonsqueezy') {
                $url = app(LemonSqueezyService::class)->createCheckout($deposit);
                return redirect()->away($url);
            }

            return redirect()->route('customer.deposit.binance', $deposit->trx);
        } catch (Exception $e) {
            return back()->with('error', 'فشل بدء عملية الشحن: ' . $e->getMessage());
        }
    }

    public function showBinanceDeposit(string $trx): View|RedirectResponse
    {
        $deposit = Deposit::query()
            ->where('trx', $trx)
            ->where('user_id', auth()->id())
            ->where('gateway', 'binance')
            ->firstOrFail();

        if ($deposit->status === 'paid') {
            return redirect()->route('customer.account')->with('success', 'تم تأكيد الإيصال وإضافة الرصيد إلى محفظتك بنجاح!');
        }

        $service = app(BinancePayService::class);
        $isExpired = $service->isExpired($deposit);
        $remainingSeconds = $service->remainingSeconds($deposit);
        $remainingAttempts = $service->remainingAttempts($deposit);

        $binanceId = (string) (env('BINANCE_PAY_MERCHANT_ID') ?: '1285662954');
        $accountName = (string) (env('BINANCE_ACCOUNT_NAME') ?: 'foon store');

        return view('store.binance-pay', compact(
            'deposit',
            'binanceId',
            'accountName',
            'isExpired',
            'remainingSeconds',
            'remainingAttempts'
        ));
    }

    public function verifyBinanceDeposit(Request $request, string $trx): RedirectResponse
    {
        $deposit = Deposit::query()
            ->where('trx', $trx)
            ->where('user_id', auth()->id())
            ->where('gateway', 'binance')
            ->firstOrFail();

        $validated = $request->validate([
            'receipt_code' => ['required', 'string', 'min:8', 'max:80'],
        ], [
            'receipt_code.required' => 'يرجى لصق كود الإيصال (رقم الطلب) من تطبيق بينانس.',
            'receipt_code.min' => 'كود الإيصال غير مكتمل، يرجى نسخ الرقم كاملاً.',
        ]);

        try {
            $creditedAmount = app(BinancePayService::class)->verifyReceiptCode(
                $deposit,
                $validated['receipt_code'],
                (string) $request->ip()
            );

            return redirect()->route('customer.account')->with(
                'success',
                "تم التحقق من إيصال USDT بنجاح! وتم شحن رصيدك بمبلغ $" . number_format($creditedAmount, 2) . " فوراً."
            );
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function cancelBinanceDeposit(string $trx): RedirectResponse
    {
        $deposit = Deposit::query()
            ->where('trx', $trx)
            ->where('user_id', auth()->id())
            ->where('gateway', 'binance')
            ->firstOrFail();

        if ($deposit->status === 'pending') {
            $deposit->status = 'failed';
            $deposit->save();
        }

        return redirect()->route('customer.account')->with('error', "تم إلغاء طلب الشحن {$deposit->trx}.");
    }

    public function buy(Request $request, Product $product, OrderCheckoutService $checkoutService): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
            'target_input' => ['nullable', 'string', 'max:500'],
        ]);

        $defaultQty = $product->isApi() ? max(1, (int) ($product->min_quantity ?? 1)) : 1;
        $quantity = (int) ($validated['quantity'] ?? $defaultQty);
        $targetInput = $validated['target_input'] ?? null;

        try {
            $order = $checkoutService->placeOrder(auth()->user(), $product, $quantity, $targetInput);

            return redirect()->route('customer.account')->with(
                'success',
                "تم شراء {$product->name} بنجاح! رقم الطلب: {$order->order_number}."
            );
        } catch (Exception $e) {
            return back()->with('error', 'تعذر إتمام عملية الشراء: ' . $e->getMessage());
        }
    }
}
