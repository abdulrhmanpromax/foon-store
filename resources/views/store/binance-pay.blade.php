<x-layouts.store>
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Top Header Banner -->
        <div class="rounded-[28px] foon-gradient text-[#0A0A0C] p-6 sm:p-8 shadow-sm border border-black/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0A0A0C] text-[#F5D020] text-[10px] font-extrabold tracking-wider uppercase" data-i18n="binance_badge">
                    دفع رقمي فوري • BINANCE PAY USDT
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" data-i18n="binance_title">
                    إتمام الشحن عبر Binance Pay
                </h1>
                <p class="text-xs sm:text-sm text-[#0A0A0C]/80 font-medium" data-i18n="binance_subtitle">
                    حوّل المبلغ المطلوب بدقة عبر تطبيق بينانس ثم الصق رقم الطلب (Order ID) للتأكيد الفوري.
                </p>
            </div>

            <div class="px-4 py-3 rounded-2xl bg-[#0A0A0C] text-white text-center shrink-0">
                <span class="block text-[10px] text-neutral-400 font-semibold uppercase" data-i18n="col_order_no">رقم الطلب</span>
                <span class="font-mono font-extrabold text-sm text-[#F5D020]">{{ $deposit->trx }}</span>
            </div>
        </div>

        @if ($isExpired)
            <div class="rounded-[28px] bg-white dark:bg-[#121216] border border-red-500/30 p-8 text-center space-y-4">
                <p class="text-sm sm:text-base font-bold text-red-600 dark:text-red-400" data-i18n="binance_expired_msg">
                    انتهت مهلة طلب الشحن هذا. يرجى العودة للمحفظة وإنشاء طلب جديد.
                </p>
                <a href="{{ route('customer.account') }}"
                   class="inline-flex px-6 py-3 rounded-2xl foon-gradient text-[#0A0A0C] font-extrabold text-xs shadow-sm">
                    <span data-i18n="nav_wallet">المحفظة وطلباتي</span>
                </a>
            </div>
        @else
            <!-- Split Bento Grid for Payment Details & Receipt Verification -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Transfer Details Card (6 Columns) -->
                <div class="lg:col-span-6 rounded-[28px] bg-white dark:bg-[#121216] border border-black/[0.07] dark:border-white/[0.08] p-6 sm:p-7 shadow-sm flex flex-col justify-between space-y-5">
                    <div class="space-y-4">
                        <!-- Amount Box -->
                        <div class="p-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.06] dark:border-white/[0.07] flex items-center justify-between">
                            <div>
                                <span class="block text-[11px] font-semibold text-neutral-500 dark:text-neutral-400" data-i18n="binance_amount_label">
                                    المبلغ المطلوب تحويله (USDT)
                                </span>
                                <span class="text-2xl sm:text-3xl font-black text-[#1D1D1F] dark:text-[#F5D020] tabular-nums">
                                    ${{ number_format((float) $deposit->amount, 2) }} <span class="text-xs font-bold">USDT</span>
                                </span>
                            </div>
                            <button type="button"
                                onclick="copyFoonText('{{ number_format((float) $deposit->amount, 2, '.', '') }}', this)"
                                class="px-3.5 py-2 rounded-xl foon-gradient text-[#0A0A0C] text-xs font-extrabold shadow-xs"
                                data-i18n="binance_copy_btn">
                                نسخ
                            </button>
                        </div>

                        <!-- Merchant Binance ID Box -->
                        <div class="p-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.06] dark:border-white/[0.07] flex items-center justify-between">
                            <div>
                                <span class="block text-[11px] font-semibold text-neutral-500 dark:text-neutral-400" data-i18n="binance_merchant_label">
                                    معرف الدفع (Binance Pay ID)
                                </span>
                                <span class="text-xl sm:text-2xl font-mono font-black text-[#1D1D1F] dark:text-white tracking-wider">
                                    {{ $binanceId }}
                                </span>
                            </div>
                            <button type="button"
                                onclick="copyFoonText('{{ $binanceId }}', this)"
                                class="px-3.5 py-2 rounded-xl foon-gradient text-[#0A0A0C] text-xs font-extrabold shadow-xs"
                                data-i18n="binance_copy_btn">
                                نسخ
                            </button>
                        </div>

                        <!-- Recipient Name -->
                        <div class="px-4 py-3 rounded-2xl bg-black/[0.03] dark:bg-white/[0.04] flex items-center justify-between text-xs">
                            <span class="text-neutral-500 dark:text-neutral-400 font-semibold" data-i18n="binance_account_label">اسم الحساب المستلم</span>
                            <span class="font-extrabold font-mono text-[#1D1D1F] dark:text-white">{{ $accountName }}</span>
                        </div>
                    </div>

                    <!-- Steps Guide -->
                    <div class="pt-4 border-t border-black/[0.06] dark:border-white/[0.07] space-y-2 text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed">
                        <p data-i18n="binance_step1">1. افتح تطبيق Binance واختر Binance Pay ثم إرسال (Send).</p>
                        <p data-i18n="binance_step2">2. الصق معرف الدفع أعلاه وأرسل المبلغ المطلوب بعملة USDT.</p>
                        <p data-i18n="binance_step3">3. انسخ رقم عملية الدفع (Order ID) من الإيصال والصقه في الحقل المقابل.</p>
                    </div>
                </div>

                <!-- Verification & Countdown Card (6 Columns) -->
                <div class="lg:col-span-6 rounded-[28px] bg-white dark:bg-[#121216] border border-black/[0.07] dark:border-white/[0.08] p-6 sm:p-7 shadow-sm flex flex-col justify-between space-y-6">
                    <!-- Countdown & Attempts Row -->
                    <div class="p-4 rounded-2xl foon-dark-gradient text-white flex items-center justify-between">
                        <div>
                            <span class="block text-[11px] text-neutral-400 font-semibold" data-i18n="binance_timer_label">
                                الوقت المتبقي لصلاحية الطلب
                            </span>
                            <span id="binance-countdown" class="text-2xl font-mono font-extrabold text-[#F5D020] tabular-nums">
                                --:--
                            </span>
                        </div>
                        <div class="text-left rtl:text-left ltr:text-right">
                            <span class="block text-[11px] text-neutral-400 font-semibold" data-i18n="binance_attempts_label">
                                المحاولات المتبقية للتحقق:
                            </span>
                            <span class="text-lg font-mono font-extrabold text-white tabular-nums">
                                {{ $remainingAttempts }}
                            </span>
                        </div>
                    </div>

                    <!-- Verify Receipt Form -->
                    <form method="POST" action="{{ route('customer.deposit.binance.verify', $deposit->trx) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-neutral-600 dark:text-neutral-300 mb-2" data-i18n="binance_receipt_label">
                                رقم عملية الدفع / كود الإيصال (Order ID)
                            </label>
                            <input type="text" name="receipt_code" value="{{ old('receipt_code') }}" required
                                data-i18n-placeholder="binance_receipt_placeholder"
                                placeholder="مثال: 294810485910293847"
                                class="w-full h-12 px-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.09] dark:border-white/[0.12] font-mono text-sm font-bold focus:outline-none focus:border-[#F5D020] transition" />
                        </div>

                        <button type="submit"
                            class="w-full h-12 rounded-2xl foon-gradient text-[#0A0A0C] hover:brightness-95 font-extrabold text-xs sm:text-sm shadow-md transition"
                            data-i18n="binance_verify_btn">
                            تأكيد الإيصال وشحن الرصيد فوراً
                        </button>
                    </form>

                    <!-- Cancel Deposit Form -->
                    <form method="POST" action="{{ route('customer.deposit.binance.cancel', $deposit->trx) }}"
                          onsubmit="return confirm('هل أنت متأكد من إلغاء طلب الشحن الحالي؟')">
                        @csrf
                        <button type="submit"
                            class="w-full py-2.5 rounded-xl bg-black/[0.04] dark:bg-white/[0.06] hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 text-xs font-bold text-neutral-500 transition"
                            data-i18n="binance_cancel_btn">
                            إلغاء طلب الشحن والعودة للمحفظة
                        </button>
                    </form>
                </div>

            </div>

            <script>
                (function () {
                    let remaining = {{ (int) $remainingSeconds }};
                    const timerEl = document.getElementById('binance-countdown');
                    function tick() {
                        if (!timerEl) return;
                        if (remaining <= 0) {
                            timerEl.textContent = '00:00';
                            window.location.reload();
                            return;
                        }
                        const mins = String(Math.floor(remaining / 60)).padStart(2, '0');
                        const secs = String(remaining % 60).padStart(2, '0');
                        timerEl.textContent = mins + ':' + secs;
                        remaining--;
                    }
                    tick();
                    setInterval(tick, 1000);
                })();
            </script>
        @endif

    </div>
</x-layouts.store>
