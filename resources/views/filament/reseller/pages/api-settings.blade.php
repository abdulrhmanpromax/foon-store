<x-filament-panels::page>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- API Key Box -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-2">مفتاح الـ API الخاص بك (X-API-KEY)</h3>
            <p class="text-xs text-gray-500 mb-4">استخدم هذا المفتاح في ترويسة الطلب <code class="bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded text-primary-600">X-API-KEY</code> لإرسال طلبات الربط البرمجي التلقائي.</p>

            <div class="flex flex-col sm:flex-row items-center gap-3">
                <input type="text" readonly value="{{ auth()->user()->api_key ?? 'لا يوجد مفتاح مولد بعد' }}"
                    class="w-full font-mono text-sm bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white select-all" />

                <button wire:click="generateApiKey" wire:confirm="هل أنت متأكد من رغبتك في توليد مفتاح جديد؟ سيتوقف المفتاح القديم عن العمل فوراً."
                    type="button"
                    class="w-full sm:w-auto shrink-0 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>توليد / تجديد المفتاح</span>
                </button>
            </div>
        </div>

        <!-- Documentation Box -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-3">توثيق نقاط الاتصال البرمجية (API Endpoints)</h3>

            <!-- Endpoint 1 -->
            <div class="space-y-2">
                <div class="flex items-center gap-2 font-mono text-xs">
                    <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold rounded-lg">GET</span>
                    <span class="text-gray-900 dark:text-white font-bold">{{ url('/api/v1/balance') }}</span>
                </div>
                <p class="text-xs text-gray-500">للاستعلام عن رصيدك الحالي في المنصة.</p>
            </div>

            <!-- Endpoint 2 -->
            <div class="space-y-2 border-t border-gray-100 dark:border-gray-800 pt-4">
                <div class="flex items-center gap-2 font-mono text-xs">
                    <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold rounded-lg">GET</span>
                    <span class="text-gray-900 dark:text-white font-bold">{{ url('/api/v1/products') }}</span>
                </div>
                <p class="text-xs text-gray-500">لجلب قائمة المنتجات المتاحة بأسعار الجملة المخفضة والمخزون المتوفر.</p>
            </div>

            <!-- Endpoint 3 -->
            <div class="space-y-2 border-t border-gray-100 dark:border-gray-800 pt-4">
                <div class="flex items-center gap-2 font-mono text-xs">
                    <span class="px-2.5 py-1 bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 font-bold rounded-lg">POST</span>
                    <span class="text-gray-900 dark:text-white font-bold">{{ url('/api/v1/orders') }}</span>
                </div>
                <p class="text-xs text-gray-500">لشراء وتنفيذ طلب فوري من رصيدك البرمجي.</p>

                <div class="bg-gray-950 text-emerald-400 p-4 rounded-xl font-mono text-xs space-y-1">
                    <p class="text-gray-400">// Headers</p>
                    <p>Content-Type: application/json</p>
                    <p>X-API-KEY: {{ auth()->user()->api_key ?? 'your_key_here' }}</p>
                    <p class="text-gray-400 pt-2">// JSON Body Example</p>
                    <p>{</p>
                    <p class="pl-4">"product_id": 1,</p>
                    <p class="pl-4">"quantity": 1,</p>
                    <p class="pl-4">"target_input": "https://instagram.com/user"</p>
                    <p>}</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
