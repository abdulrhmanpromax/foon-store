<?php

namespace App\Services\Payments;

use App\Models\Deposit;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class BinancePayService
{
    public const EXPIRY_MINUTES = 10;
    public const MAX_ATTEMPTS = 5;

    protected string $apiKey;
    protected string $secretKey;

    public function __construct()
    {
        $this->apiKey = (string) (config('services.binance.api_key') ?: env('BINANCE_PAY_API_KEY', ''));
        $this->secretKey = (string) (config('services.binance.secret_key') ?: env('BINANCE_PAY_SECRET_KEY', ''));
    }

    public function isExpired(Deposit $deposit): bool
    {
        if ($deposit->status !== 'pending') {
            return $deposit->status === 'failed';
        }

        if ($deposit->created_at->addMinutes(self::EXPIRY_MINUTES)->isPast()) {
            $deposit->status = 'failed';
            $deposit->save();
            return true;
        }

        return false;
    }

    public function remainingSeconds(Deposit $deposit): int
    {
        $expiresAt = $deposit->created_at->copy()->addMinutes(self::EXPIRY_MINUTES);
        return max(0, now()->diffInSeconds($expiresAt, false));
    }

    public function remainingAttempts(Deposit $deposit): int
    {
        $used = (int) Cache::get("deposit_attempts_{$deposit->id}", 0);
        return max(0, self::MAX_ATTEMPTS - $used);
    }

    /**
     * Strict USDT-only verification with anti-fraud, time-window, replay-lock, and rate-limit protections.
     *
     * @throws Exception
     */
    public function verifyReceiptCode(Deposit $deposit, string $rawCode, string $clientIp = ''): float
    {
        if ($deposit->status === 'paid') {
            return (float) $deposit->amount;
        }

        // 1. Account & IP global brute-force shield (max 10 failed tries per hour across all orders)
        $globalFraudKey = "binance_fraud_shield_{$deposit->user_id}_{$clientIp}";
        if (RateLimiter::tooManyAttempts($globalFraudKey, 10)) {
            $seconds = RateLimiter::availableIn($globalFraudKey);
            $mins = max(1, (int) ceil($seconds / 60));
            throw new Exception("تم إيقاف التحقق مؤقتاً لمدة {$mins} دقيقة بسبب تكرار إدخال أكواد غير صحيحة.");
        }

        // 2. Enforce strict 10-minute server-side expiration
        if ($this->isExpired($deposit)) {
            throw new Exception('انتهت مهلة الدفع (10 دقائق) وتم إلغاء هذا الطلب تلقائياً. يرجى إنشاء طلب شحن جديد.');
        }

        // 3. Enforce per-order attempt limit (max 5 tries)
        $attemptKey = "deposit_attempts_{$deposit->id}";
        $attempts = (int) Cache::get($attemptKey, 0);
        if ($attempts >= self::MAX_ATTEMPTS) {
            $deposit->status = 'failed';
            $deposit->save();
            Log::warning('Fraud Protection: Order cancelled after max failed attempts', [
                'deposit_trx' => $deposit->trx,
                'user_id' => $deposit->user_id,
                'ip' => $clientIp,
            ]);
            throw new Exception('تم إلغاء الطلب تلقائياً لتجاوز الحد الأقصى لمحاولات التحقق (5 محاولات).');
        }

        // 4. Sanitize & validate receipt format
        $code = preg_replace('/\s+/', '', trim($rawCode));
        if ($code === '' || ! preg_match('/^[A-Za-z0-9_-]{8,80}$/', $code)) {
            $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, 'invalid_format');
            throw new Exception('صيغة كود الإيصال غير صحيحة. يرجى نسخ رقم الطلب (Order ID) من إيصال بينانس دون مسافات أو رموز.');
        }

        if (empty($this->apiKey) || empty($this->secretKey)) {
            throw new Exception('إعدادات الربط مع Binance غير مكتملة في السيرفر.');
        }

        // 5. Block immediate replay of submitted code
        $alreadyUsedRaw = Deposit::query()
            ->where('gateway_order_id', $code)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyUsedRaw) {
            $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, 'duplicate_raw_code');
            throw new Exception('تم استخدام كود الإيصال هذا مسبقاً لشحن رصيد.');
        }

        // Only allow transfers executed within the active order window (60s clock tolerance)
        $minValidTimestampMs = ($deposit->created_at->timestamp - 60) * 1000;
        $searchStartMs = ($deposit->created_at->timestamp - 300) * 1000;

        // 6. Verify against Binance Pay incoming history (USDT ONLY)
        $matched = $this->findInPayTransactions($code, $searchStartMs, $minValidTimestampMs, $deposit, $attemptKey, $globalFraudKey, $attempts, $clientIp);

        // 7. Fallback: Verify against On-Chain USDT Spot deposits (USDT ONLY, status = 1)
        if (! $matched) {
            $matched = $this->findInSpotDeposits($code, $searchStartMs, $minValidTimestampMs, $deposit, $attemptKey, $globalFraudKey, $attempts, $clientIp);
        }

        if (! $matched) {
            $left = $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, 'not_found_or_old');
            throw new Exception("لم يتم العثور على حوالة USDT واردة حديثة مطابقة لهذا الكود. تأكد من التحويل بعملة USDT إلى حساب foon store ثم نسخ رقم الطلب الصحيح. (المحاولات المتبقية: {$left})");
        }

        $canonicalId = $matched['id'];
        $receivedAmount = round((float) $matched['amount'], 2);
        $requiredAmount = round((float) $deposit->amount, 2);

        // 8. Strictly reject underpayment
        if ($receivedAmount + 0.001 < $requiredAmount) {
            $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, "underpaid:{$receivedAmount}<{$requiredAmount}");
            throw new Exception("المبلغ المستلم ({$receivedAmount} USDT) أقل من قيمة الطلب ({$requiredAmount} USDT). يرجى التحويل بالمبلغ الكامل.");
        }

        // 9. Atomic database lock & single-use canonical ID enforcement
        return DB::transaction(function () use ($deposit, $canonicalId, $receivedAmount, $attemptKey) {
            $lockedDeposit = Deposit::query()
                ->where('id', $deposit->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedDeposit->status === 'paid') {
                return (float) $lockedDeposit->amount;
            }

            if ($lockedDeposit->status !== 'pending') {
                throw new Exception('حالة هذا الطلب لم تعد صالحة للشحن.');
            }

            $duplicateCanonical = Deposit::query()
                ->where('gateway_order_id', $canonicalId)
                ->where('id', '!=', $lockedDeposit->id)
                ->exists();

            if ($duplicateCanonical) {
                throw new Exception('تم استخدام كود الإيصال هذا مسبقاً في عملية أخرى.');
            }

            $lockedDeposit->amount = $receivedAmount;
            $lockedDeposit->status = 'paid';
            $lockedDeposit->gateway_order_id = $canonicalId;
            $lockedDeposit->save();

            $user = User::query()->lockForUpdate()->findOrFail($lockedDeposit->user_id);
            $user->balance = bcadd((string) $user->balance, (string) $receivedAmount, 2);
            $user->save();

            Cache::forget($attemptKey);

            return $receivedAmount;
        });
    }

    protected function recordFailedAttempt(
        Deposit $deposit,
        string $attemptKey,
        string $globalFraudKey,
        int $currentAttempts,
        string $code,
        string $ip,
        string $reason
    ): int {
        $next = $currentAttempts + 1;
        Cache::put($attemptKey, $next, now()->addHours(2));
        RateLimiter::hit($globalFraudKey, 3600);

        Log::warning('Binance USDT Verification Rejected', [
            'deposit_trx' => $deposit->trx,
            'user_id' => $deposit->user_id,
            'ip' => $ip,
            'submitted_code' => $code,
            'attempt' => $next,
            'reason' => $reason,
        ]);

        if ($next >= self::MAX_ATTEMPTS) {
            $deposit->status = 'failed';
            $deposit->save();
        }

        return max(0, self::MAX_ATTEMPTS - $next);
    }

    /**
     * @return array{id: string, amount: float}|null
     * @throws Exception
     */
    protected function findInPayTransactions(
        string $code,
        int $searchStartMs,
        int $minValidTimestampMs,
        Deposit $deposit,
        string $attemptKey,
        string $globalFraudKey,
        int $attempts,
        string $clientIp
    ): ?array {
        $timestamp = (string) round(microtime(true) * 1000);
        $query = http_build_query([
            'startTime' => $searchStartMs,
            'limit' => 100,
            'timestamp' => $timestamp,
        ]);
        $signature = hash_hmac('sha256', $query, $this->secretKey);

        $response = Http::withHeaders([
            'X-MBX-APIKEY' => $this->apiKey,
        ])->get("https://api.binance.com/sapi/v1/pay/transactions?{$query}&signature={$signature}");

        if (! $response->successful()) {
            Log::error('Binance Pay API Error: ' . $response->body());
            $msg = (string) $response->json('msg', '');
            if (str_contains($msg, 'IP')) {
                throw new Exception('تعذر الاتصال ببوابة بينانس بسبب إعدادات IP السيرفر.');
            }
            return null;
        }

        $items = $response->json('data', []);
        if (! is_array($items)) {
            return null;
        }

        foreach ($items as $item) {
            $orderId = trim((string) ($item['orderId'] ?? ''));
            $txId = trim((string) ($item['transactionId'] ?? ''));

            $matchesOrder = $orderId !== '' && (strcasecmp($orderId, $code) === 0 || str_ends_with($orderId, $code));
            $matchesTx = $txId !== '' && (strcasecmp($txId, $code) === 0 || str_ends_with($txId, $code));

            if (! $matchesOrder && ! $matchesTx) {
                continue;
            }

            $currency = strtoupper(trim((string) ($item['currency'] ?? '')));
            $amount = (float) ($item['amount'] ?? 0);
            $txTime = (int) ($item['transactionTime'] ?? 0);

            // Reject outgoing transfers
            if ($amount <= 0) {
                $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, 'outgoing_transfer');
                throw new Exception('رقم العملية المدخل يخص حوالة صادرة وليس حوالة واردة إلى حساب foon store.');
            }

            // Strictly enforce USDT ONLY
            if ($currency !== 'USDT') {
                $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, "non_usdt_currency:{$currency}");
                throw new Exception("تم رفض العملية: التحويل تم بعملة ({$currency}). الدفع مقبول بعملة USDT الحقيقية فقط.");
            }

            // Strictly reject transfers made before this deposit order was created
            if ($txTime > 0 && $txTime < $minValidTimestampMs) {
                $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, 'old_receipt_recycled');
                throw new Exception('هذا الإيصال يعود لحوالة قديمة تمت قبل إنشاء طلب الشحن الحالي. يرجى إجراء تحويل جديد.');
            }

            $canonical = $orderId !== '' ? $orderId : $txId;

            return [
                'id' => $canonical,
                'amount' => $amount,
            ];
        }

        return null;
    }

    /**
     * @return array{id: string, amount: float}|null
     * @throws Exception
     */
    protected function findInSpotDeposits(
        string $code,
        int $searchStartMs,
        int $minValidTimestampMs,
        Deposit $deposit,
        string $attemptKey,
        string $globalFraudKey,
        int $attempts,
        string $clientIp
    ): ?array {
        $timestamp = (string) round(microtime(true) * 1000);
        $query = http_build_query([
            'coin' => 'USDT',
            'status' => 1,
            'startTime' => $searchStartMs,
            'timestamp' => $timestamp,
        ]);
        $signature = hash_hmac('sha256', $query, $this->secretKey);

        $response = Http::withHeaders([
            'X-MBX-APIKEY' => $this->apiKey,
        ])->get("https://api.binance.com/sapi/v1/capital/deposit/hisrec?{$query}&signature={$signature}");

        if (! $response->successful()) {
            return null;
        }

        $items = $response->json();
        if (! is_array($items)) {
            return null;
        }

        foreach ($items as $item) {
            $coin = strtoupper(trim((string) ($item['coin'] ?? '')));
            $status = (int) ($item['status'] ?? 0);
            $amount = (float) ($item['amount'] ?? 0);
            $insertTime = (int) ($item['insertTime'] ?? 0);
            $txId = trim((string) ($item['txId'] ?? ''));
            $depId = trim((string) ($item['id'] ?? ''));

            if ($coin !== 'USDT' || $status !== 1 || $amount <= 0) {
                continue;
            }

            if (($txId !== '' && strcasecmp($txId, $code) === 0) || ($depId !== '' && strcasecmp($depId, $code) === 0)) {
                if ($insertTime > 0 && $insertTime < $minValidTimestampMs) {
                    $this->recordFailedAttempt($deposit, $attemptKey, $globalFraudKey, $attempts, $code, $clientIp, 'old_spot_tx_recycled');
                    throw new Exception('معرف الشبكة (TxID) يعود لإيداع قديم تم قبل إنشاء هذا الطلب.');
                }

                return [
                    'id' => $txId !== '' ? $txId : $depId,
                    'amount' => $amount,
                ];
            }
        }

        return null;
    }

    public function verifyAndProcessWebhook(array $headers, string $rawBody): bool
    {
        return true;
    }
}
