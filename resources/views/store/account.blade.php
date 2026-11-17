<x-layouts.store>
    <div class="space-y-8">

        <!-- Top Bento Row: Apple Wallet Balance Card + Instant Top-Up -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Apple Cash / Foon Gold Wallet Card (4 Columns) -->
            <div class="lg:col-span-4 rounded-[28px] foon-dark-gradient text-white p-7 flex flex-col justify-between relative overflow-hidden shadow-md border border-white/10 min-h-[230px]">
                <div class="absolute -top-12 -left-12 w-40 h-40 rounded-full bg-[#F5D020]/20 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex items-center justify-between">
                    <span class="text-xs font-semibold text-neutral-400" data-i18n="wallet_card_label">رصيد المحفظة المتاح</span>
                    <span class="px-2.5 py-1 rounded-full bg-[#F5D020] text-[#0A0A0C] text-[10px] font-extrabold font-mono">FOON PAY</span>
                </div>

                <div class="relative z-10 my-6">
                    <div class="text-4xl sm:text-5xl font-extrabold tracking-tight text-[#F5D020] tabular-nums">
                        ${{ number_format((float) $user->balance, 2) }}
                    </div>
                </div>

                <div class="relative z-10 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-neutral-300">
                    <span class="font-bold truncate">{{ $user->name }}</span>
                    <span class="text-neutral-400 font-mono truncate">{{ $user->email }}</span>
                </div>
            </div>

            <!-- Instant Wallet Top-Up Card (8 Columns) -->
            <div class="lg:col-span-8 rounded-[28px] bg-white dark:bg-[#121216] border border-black/[0.07] dark:border-white/[0.08] p-7 shadow-sm flex flex-col justify-between">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <h2 class="text-lg font-extrabold text-[#1D1D1F] dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F5D020]"></span>
                        <span data-i18n="wallet_topup_title">شحن الرصيد الفوري</span>
                    </h2>

                    <!-- Quick Amount Chips -->
                    <div class="flex items-center gap-1.5">
                        @foreach ([5, 10, 25, 50] as $preset)
                            <button type="button"
                                onclick="document.getElementById('deposit-amount-input').value = '{{ $preset }}'"
                                class="px-3 py-1 rounded-full text-xs font-bold tabular-nums bg-[#F5F5F7] dark:bg-[#1C1C22] hover:bg-[#F5D020] hover:text-[#0A0A0C] border border-black/[0.06] dark:border-white/[0.08] transition">
                                ${{ $preset }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <form method="POST" action="{{ route('customer.deposit') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                    @csrf
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1.5" data-i18n="wallet_amount_label">
                            المبلغ بالدولار ($)
                        </label>
                        <input type="number" id="deposit-amount-input" name="amount" step="0.01" min="1" max="10000" value="10" required
                            class="w-full h-11 px-4 rounded-xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] font-bold tabular-nums text-sm focus:outline-none focus:border-[#F5D020]" />
                    </div>

                    <div class="sm:col-span-5">
                        <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1.5" data-i18n="wallet_gateway_label">
                            بوابة الدفع
                        </label>
                        <select name="gateway" required
                            class="w-full h-11 px-3.5 rounded-xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] font-semibold text-xs focus:outline-none focus:border-[#F5D020]">
                            <option value="lemonsqueezy">Visa / Mastercard / Apple Pay (Lemon Squeezy)</option>
                            <option value="binance">Binance Pay (USDT فوري)</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3">
                        <button type="submit"
                            class="w-full h-11 px-5 rounded-xl bg-[#F5D020] text-[#0A0A0C] hover:brightness-95 font-extrabold text-xs shadow-sm transition"
                            data-i18n="wallet_btn_pay">
                            متابعة الدفع الآمن
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders & Purchases Table Card -->
        <div class="rounded-[28px] bg-white dark:bg-[#121216] border border-black/[0.07] dark:border-white/[0.08] p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-extrabold text-[#1D1D1F] dark:text-white mb-6" data-i18n="orders_title">
                طلباتي ومشترياتي السابقة
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-right rtl:text-right ltr:text-left border-collapse">
                    <thead>
                        <tr class="border-b border-black/[0.06] dark:border-white/[0.08] text-[11px] font-bold text-neutral-400 uppercase">
                            <th class="pb-3.5 px-3" data-i18n="col_order_no">رقم الطلب</th>
                            <th class="pb-3.5 px-3" data-i18n="col_product">المنتج</th>
                            <th class="pb-3.5 px-3" data-i18n="col_qty">الكمية</th>
                            <th class="pb-3.5 px-3" data-i18n="col_total">الإجمالي</th>
                            <th class="pb-3.5 px-3" data-i18n="col_status">الحالة</th>
                            <th class="pb-3.5 px-3" data-i18n="col_delivery">الكود / بيانات التسليم</th>
                            <th class="pb-3.5 px-3" data-i18n="col_date">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[0.05] dark:divide-white/[0.06] text-xs">
                        @forelse ($orders as $order)
                            @php
                                $deliveryText = null;
                                if (!empty($order->decrypted_delivery_data)) {
                                    $deliveryText = $order->decrypted_delivery_data;
                                } elseif (!empty($order->delivery_data)) {
                                    try {
                                        $deliveryText = \Illuminate\Support\Facades\Crypt::decryptString($order->delivery_data);
                                    } catch (\Throwable $e) {
                                        try {
                                            $deliveryText = decrypt($order->delivery_data);
                                        } catch (\Throwable $e2) {
                                            $deliveryText = $order->delivery_data;
                                        }
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-black/[0.015] dark:hover:bg-white/[0.02] transition">
                                <td class="py-4 px-3 font-mono font-bold text-neutral-500">{{ $order->order_number }}</td>
                                <td class="py-4 px-3 font-bold text-[#1D1D1F] dark:text-white">{{ $order->product?->name ?? '-' }}</td>
                                <td class="py-4 px-3 tabular-nums font-semibold">{{ number_format((int) $order->quantity) }}</td>
                                <td class="py-4 px-3 tabular-nums font-extrabold text-[#1D1D1F] dark:text-[#F5D020]">${{ number_format((float) $order->total_price, 2) }}</td>
                                <td class="py-4 px-3">
                                    @if ($order->status === 'completed')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/15 text-emerald-700 dark:text-emerald-300" data-i18n="status_completed">مكتمل</span>
                                    @elseif ($order->status === 'processing')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/15 text-amber-700 dark:text-amber-300" data-i18n="status_processing">قيد التنفيذ</span>
                                    @elseif ($order->status === 'failed')
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-500/15 text-red-700 dark:text-red-300" data-i18n="status_failed">ملغي / مسترد</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-neutral-500/15 text-neutral-600 dark:text-neutral-300" data-i18n="status_pending">قيد الانتظار</span>
                                    @endif
                                </td>
                                <td class="py-4 px-3 max-w-xs">
                                    @if ($deliveryText)
                                        <div class="flex items-center gap-2">
                                            <code class="px-2.5 py-1 rounded-lg bg-[#F5F5F7] dark:bg-[#1C1C22] font-mono text-[11px] truncate">{{ $deliveryText }}</code>
                                            <button type="button" onclick="navigator.clipboard.writeText(@js($deliveryText)); this.textContent='✓';"
                                                class="px-2 py-1 rounded-lg bg-[#F5D020] text-[#0A0A0C] text-[10px] font-bold shrink-0">
                                                نسخ
                                            </button>
                                        </div>
                                    @elseif ($order->target_input)
                                        <span class="text-neutral-400 font-mono text-[11px] truncate block">{{ $order->target_input }}</span>
                                    @else
                                        <span class="text-neutral-400">...</span>
                                    @endif
                                </td>
                                <td class="py-4 px-3 tabular-nums text-neutral-400">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-neutral-400 font-medium">
                                    لا توجد طلبات سابقة حتى الآن.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.store>
