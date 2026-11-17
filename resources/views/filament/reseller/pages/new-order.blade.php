<x-filament-panels::page>
    <div class="max-w-3xl mx-auto space-y-6">
        @if ($deliveredCredentials)
            <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-3 text-emerald-800 dark:text-emerald-300 font-bold text-lg mb-3">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>تم تسليم الكود / البيانات بنجاح:</span>
                </div>
                <div class="bg-white dark:bg-gray-900 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 font-mono text-sm break-all text-gray-900 dark:text-white select-all">
                    {{ $deliveredCredentials }}
                </div>
                <p class="text-xs text-gray-500 mt-2">يمكنك دائماً العودة إلى صفحة "طلباتي" لنسخ الأكواد في أي وقت.</p>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <form wire:submit="submitOrder" class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">اختر المنتج</label>
                    <select wire:model.live="productId" required
                        class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-3 px-4 focus:ring-2 focus:ring-primary-500 font-medium">
                        <option value="">-- اختر من قائمة منتجات الجملة --</option>
                        @foreach ($this->products as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->name }} — سعر الموزع: ${{ number_format((float) $p->reseller_price, 2) }}
                                (السعر للعميل: ${{ number_format((float) $p->price, 2) }})
                                @if ($p->isVault()) [المخزون المتوفر: {{ $p->available_vault_items_count }}] @else [ربط مباشر API] @endif
                            </option>
                        @endforeach
                    </select>
                    @error('productId') <span class="text-xs text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if ($this->selectedProduct)
                    <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <span class="text-xs text-gray-500 block">سعر القطعة للموزع</span>
                            <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400">${{ number_format((float) $this->selectedProduct->reseller_price, 2) }}</span>
                        </div>
                        <div class="text-left">
                            <span class="text-xs text-gray-500 block">طريقة التسليم</span>
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $this->selectedProduct->isVault() ? 'تسليم فوري من المخزون' : 'تنفيذ فوري عبر الـ API' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">الكمية</label>
                            <input type="number" min="1" wire:model.live="quantity" required
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2.5 px-4 focus:ring-2 focus:ring-primary-500 font-bold" />
                            @error('quantity') <span class="text-xs text-danger-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                {{ $this->selectedProduct->isApi() ? 'الرابط / الحساب / الدولة (مطلوب للـ API)' : 'ملاحظات الطلب (اختياري)' }}
                            </label>
                            <input type="text" wire:model="targetInput"
                                placeholder="{{ $this->selectedProduct->isApi() ? 'https://instagram.com/user أو +1' : 'اختياري' }}"
                                class="w-full rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white py-2.5 px-4 focus:ring-2 focus:ring-primary-500" />
                        </div>
                    </div>

                    <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 rounded-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-500 block">الإجمالي المخصوم من محفظتك:</span>
                            <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">
                                ${{ number_format((float) bcmul((string) $this->selectedProduct->reseller_price, (string) max(1, $quantity), 2), 2) }}
                            </span>
                        </div>
                        <button type="submit"
                            class="px-8 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition">
                            تأكيد الشراء الفوري
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</x-filament-panels::page>
