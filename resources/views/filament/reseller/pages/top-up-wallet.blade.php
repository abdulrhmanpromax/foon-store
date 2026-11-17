<x-filament-panels::page>
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
        <form wire:submit="submit" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">المبلغ المراد شحنه (USD $)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-500 font-bold">$</span>
                    <input type="number" step="0.5" min="1" wire:model="amount" required
                        class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 font-semibold text-lg"
                        placeholder="50.00" />
                </div>
                @error('amount') <span class="text-xs text-danger-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">اختر بوابة الدفع</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition {{ $gateway === 'lemonsqueezy' ? 'border-primary-500 bg-primary-50/20 dark:bg-primary-950/20' : 'border-gray-200 dark:border-gray-800' }}">
                        <input type="radio" wire:model.live="gateway" value="lemonsqueezy" class="text-primary-600 focus:ring-primary-500" />
                        <div class="mr-3">
                            <span class="block text-sm font-bold text-gray-900 dark:text-white">Lemon Squeezy</span>
                            <span class="block text-xs text-gray-500">بطاقات الائتمان / Visa / Mastercard / Apple Pay</span>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border rounded-xl cursor-pointer transition {{ $gateway === 'binance' ? 'border-primary-500 bg-primary-50/20 dark:bg-primary-950/20' : 'border-gray-200 dark:border-gray-800' }}">
                        <input type="radio" wire:model.live="gateway" value="binance" class="text-primary-600 focus:ring-primary-500" />
                        <div class="mr-3">
                            <span class="block text-sm font-bold text-gray-900 dark:text-white">Binance Pay</span>
                            <span class="block text-xs text-gray-500">العملات الرقمية الفورية (USDT / Crypto)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                <button type="submit"
                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2">
                    <span>متابعة الدفع الآن</span>
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
