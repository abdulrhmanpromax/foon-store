<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class BalanceService
{
    /**
     * Add balance to a user account safely using transaction and lock.
     */
    public function addBalance(int $userId, float $amount): User
    {
        if ($amount <= 0) {
            throw new Exception('المبلغ المضاف يجب أن يكون أكبر من الصفر.');
        }

        return DB::transaction(function () use ($userId, $amount) {
            $user = User::query()->lockForUpdate()->findOrFail($userId);
            $user->balance = bcadd((string) $user->balance, (string) $amount, 2);
            $user->save();

            return $user;
        });
    }

    /**
     * Deduct balance from a user account safely using transaction and lock.
     */
    public function deductBalance(int $userId, float $amount): User
    {
        if ($amount <= 0) {
            throw new Exception('المبلغ المخصوم يجب أن يكون أكبر من الصفر.');
        }

        return DB::transaction(function () use ($userId, $amount) {
            $user = User::query()->lockForUpdate()->findOrFail($userId);

            if (bccomp((string) $user->balance, (string) $amount, 2) < 0) {
                throw new Exception('الرصيد غير كافٍ لإتمام العملية.');
            }

            $user->balance = bcsub((string) $user->balance, (string) $amount, 2);
            $user->save();

            return $user;
        });
    }
}
