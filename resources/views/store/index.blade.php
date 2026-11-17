<x-layouts.store>
    <div class="space-y-10">

        <!-- Apple Bento Grid Hero with Foon Visual Identity -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            <!-- Primary Foon Brand Banner (8 Columns) -->
            <div class="lg:col-span-8 relative overflow-hidden rounded-[28px] foon-gradient text-[#0A0A0C] p-8 sm:p-10 lg:p-12 shadow-sm border border-black/[0.08] flex flex-col justify-between min-h-[290px]">
                <!-- Subtle Watermark Line Icons (Matching Foon Identity Banner) -->
                <svg class="w-24 h-24 absolute top-5 left-8 opacity-[0.10] pointer-events-none -rotate-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z"/>
                </svg>
                <svg class="w-28 h-28 absolute bottom-4 left-1/3 opacity-[0.09] pointer-events-none rotate-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z"/>
                </svg>
                <svg class="w-24 h-24 absolute bottom-5 right-8 opacity-[0.10] pointer-events-none -rotate-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>

                <div class="relative z-10 space-y-4 max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#0A0A0C] text-[#F5D020] text-[11px] font-bold tracking-wider uppercase">
                        <span class="w-1.5 h-1.5 rounded-full foon-gradient animate-pulse"></span>
                        <span data-i18n="hero_badge">FOON STORE • تسليم رقمي فوري 24/7</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-[40px] font-extrabold tracking-tight leading-[1.18] text-[#0A0A0C]" data-i18n="hero_title">
                        متجرك المضمون للاشتراكات والخدمات الرقمية.
                    </h1>

                    <p class="text-sm sm:text-base text-[#0A0A0C]/80 font-medium max-w-xl leading-relaxed" data-i18n="hero_subtitle">
                        أكواد أصلية، اشتراكات بريميوم، وخدمات تواصل اجتماعي بتنفيذ فوري مؤتمت بالكامل وبأعلى معايير الأمان.
                    </p>
                </div>

                <div class="relative z-10 pt-6 mt-4 flex flex-wrap items-center justify-between gap-4 border-t border-black/10">
                    <!-- Brand Lockup Signature -->
                    <div class="flex items-center gap-3">
                        <span class="font-black text-2xl tracking-tight font-mono text-[#0A0A0C]">Foon</span>
                        <span class="h-6 w-[2px] bg-[#0A0A0C]"></span>
                        <div class="leading-tight">
                            <span class="block text-xs font-extrabold text-[#0A0A0C]">Foon Store</span>
                            <span class="block text-xs font-bold text-[#0A0A0C]/80" data-i18n="brand_tagline">متجرك المضمون</span>
                        </div>
                    </div>

                    @guest
                        <a href="{{ route('register') }}"
                           class="px-6 py-3 rounded-full bg-[#0A0A0C] text-white hover:bg-neutral-800 text-xs font-bold shadow-md transition"
                           data-i18n="hero_cta_guest">
                            ابدأ الآن واشحن محفظتك
                        </a>
                    @else
                        <a href="{{ route('customer.account') }}"
                           class="px-6 py-3 rounded-full bg-[#0A0A0C] text-white hover:bg-neutral-800 text-xs font-bold shadow-md transition"
                           data-i18n="hero_cta_auth">
                            إدارة المحفظة والرصيد
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Side Apple Bento Stack (4 Columns) -->
            <div class="lg:col-span-4 flex flex-col gap-5 justify-between">
                <div class="bento-card flex-1 rounded-[28px] bg-white dark:bg-[#121216] border border-black/[0.07] dark:border-white/[0.08] p-6 flex flex-col justify-between shadow-sm">
                    <div class="w-10 h-10 rounded-2xl foon-gradient/20 text-[#0A0A0C] dark:text-[#F5D020] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <h3 class="font-bold text-base text-[#1D1D1F] dark:text-white" data-i18n="bento_instant_title">تسليم لحظي 24/7</h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1 leading-relaxed" data-i18n="bento_instant_desc">
                            سحب تلقائي من الخزنة أو إرسال فوري عبر API.
                        </p>
                    </div>
                </div>

                <div class="bento-card flex-1 rounded-[28px] bg-white dark:bg-[#121216] border border-black/[0.07] dark:border-white/[0.08] p-6 flex flex-col justify-between shadow-sm">
                    <div class="w-10 h-10 rounded-2xl foon-gradient/20 text-[#0A0A0C] dark:text-[#F5D020] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <div class="mt-4">
                        <h3 class="font-bold text-base text-[#1D1D1F] dark:text-white" data-i18n="bento_payment_title">بوابات دفع عالمية</h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1 leading-relaxed" data-i18n="bento_payment_desc">
                            شحن فوري عبر Apple Pay وVisa وMastercard وUSDT.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Bar & Search (Apple Segmented Control Style) -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-2">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-extrabold tracking-tight text-[#1D1D1F] dark:text-white" data-i18n="section_products">
                    المنتجات والخدمات المتاحة
                </h2>
                <span class="px-3 py-1 rounded-full text-xs font-bold foon-gradient text-[#0A0A0C] tabular-nums">
                    {{ $products->count() }}
                </span>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Segmented Pills -->
                <div class="inline-flex p-1 rounded-full bg-black/[0.05] dark:bg-white/[0.07] border border-black/[0.05] dark:border-white/[0.06]">
                    <button type="button" onclick="filterFoonProducts('all', this)"
                        class="foon-filter-btn px-4 py-1.5 rounded-full text-xs font-bold transition bg-white dark:bg-[#1D1D22] text-[#0A0A0C] dark:text-white shadow-sm"
                        data-i18n="filter_all">
                        الكل
                    </button>
                    <button type="button" onclick="filterFoonProducts('vault', this)"
                        class="foon-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white transition"
                        data-i18n="filter_vault">
                        تسليم فوري (مخزون)
                    </button>
                    <button type="button" onclick="filterFoonProducts('api', this)"
                        class="foon-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white transition"
                        data-i18n="filter_api">
                        تنفيذ مباشر (API)
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative flex-1 sm:w-60">
                    <input type="text" id="foon-product-search" oninput="searchFoonProducts(this.value)"
                        data-i18n-placeholder="search_placeholder"
                        placeholder="ابحث عن اشتراك أو خدمة..."
                        class="w-full h-9 pl-4 pr-4 rounded-full bg-white dark:bg-[#121216] border border-black/[0.08] dark:border-white/[0.1] text-xs focus:outline-none focus:border-[#F5D020] transition" />
                </div>
            </div>
        </div>

        <!-- Apple Bento Products Grid -->
        <div id="foon-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($products as $product)
                @php
                    $minQty = max(1, (int) ($product->min_quantity ?? 1));
                    $maxQty = max($minQty, (int) ($product->max_quantity ?? 100000));
                    $initialTotal = $product->isApi()
                        ? max(0.01, round(((float) $product->price * $minQty) / 1000, 2))
                        : (float) $product->price;
                @endphp
                <div class="foon-product-card bento-card rounded-[26px] bg-white dark:bg-[#121216] border border-black/[0.07] dark:border-white/[0.08] p-6 flex flex-col justify-between shadow-sm"
                     data-delivery="{{ $product->delivery_type }}"
                     data-name="{{ mb_strtolower($product->name . ' ' . ($product->description ?? '')) }}">

                    <div>
                        <!-- Top Meta Row -->
                        <div class="flex items-center justify-between gap-2 mb-4">
                            @if ($product->isVault())
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold foon-gradient/20 text-[#0A0A0C] dark:text-[#F5D020]">
                                    <span class="w-1.5 h-1.5 rounded-full foon-gradient"></span>
                                    <span data-i18n="badge_vault">تسليم فوري • مخزون</span>
                                </span>
                                <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400 tabular-nums">
                                    <span data-i18n="label_available">متوفر:</span> {{ $product->available_vault_items_count }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-black/[0.06] dark:bg-white/[0.09] text-[#1D1D1F] dark:text-white">
                                    <span class="w-1.5 h-1.5 rounded-full foon-gradient"></span>
                                    <span data-i18n="badge_api">تنفيذ مباشر • API</span>
                                </span>
                                <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400 tabular-nums">
                                    <span data-i18n="label_limits">الحد:</span> {{ number_format($minQty) }} - {{ number_format($maxQty) }}
                                </span>
                            @endif
                        </div>

                        <!-- Product Title & Description -->
                        <h3 class="font-bold text-lg text-[#1D1D1F] dark:text-white mb-1.5 leading-snug">
                            {{ $product->name }}
                        </h3>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-5 line-clamp-2 leading-relaxed">
                            {{ $product->description ?? 'تسليم فوري وآمن بأعلى جودة وسرعة.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-black/[0.06] dark:border-white/[0.07] space-y-4">
                        <!-- Pricing Display -->
                        <div class="flex items-end justify-between">
                            <div>
                                <span class="text-[11px] font-medium text-neutral-400 block mb-0.5"
                                      data-i18n="{{ $product->isApi() ? 'label_price_1k' : 'label_price_unit' }}">
                                    {{ $product->isApi() ? 'السعر لكل 1,000' : 'السعر' }}
                                </span>
                                <span class="text-2xl font-extrabold tracking-tight text-[#1D1D1F] dark:text-white tabular-nums">
                                    ${{ number_format((float) $product->price, 2) }}
                                </span>
                            </div>

                            @if ($product->isApi())
                                <div class="text-left rtl:text-left ltr:text-right">
                                    <span class="text-[11px] font-medium text-neutral-400 block mb-0.5" data-i18n="label_total_due">الإجمالي المطلوب</span>
                                    <span id="total-price-{{ $product->id }}"
                                          class="text-lg font-extrabold px-2.5 py-0.5 rounded-lg foon-gradient/25 text-[#0A0A0C] dark:text-[#F5D020] tabular-nums">
                                        ${{ number_format($initialTotal, 2) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Purchase Action Form -->
                        @auth
                            <form method="POST" action="{{ route('customer.buy', $product) }}"
                                  onsubmit="return confirm('تأكيد إرسال الطلب لخدمة {{ addslashes($product->name) }}؟')"
                                  class="space-y-2.5">
                                @csrf
                                @if ($product->isApi())
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        <input type="text" name="target_input" required
                                               data-i18n-placeholder="placeholder_target"
                                               placeholder="الرابط أو الحساب المطلوب..."
                                               class="sm:col-span-2 w-full text-xs py-2.5 px-3.5 rounded-xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] focus:outline-none focus:border-[#F5D020]" />

                                        <input type="number" name="quantity" required
                                               min="{{ $minQty }}" max="{{ $maxQty }}" value="{{ $minQty }}"
                                               data-unit-price="{{ (float) $product->price }}"
                                               data-total-target="total-price-{{ $product->id }}"
                                               oninput="updateSmmTotal(this)"
                                               data-i18n-placeholder="placeholder_qty"
                                               placeholder="الكمية"
                                               class="w-full text-xs py-2.5 px-3 rounded-xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] font-bold tabular-nums focus:outline-none focus:border-[#F5D020]" />
                                    </div>
                                @endif

                                <button type="submit"
                                    class="w-full py-3 px-4 rounded-xl bg-[#0A0A0C] dark:foon-gradient text-white dark:text-[#0A0A0C] hover:opacity-90 text-xs font-bold tracking-wide shadow-sm transition"
                                    data-i18n="btn_buy_now">
                                    شراء الآن
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                               class="block text-center w-full py-3 px-4 rounded-xl bg-black/[0.05] dark:bg-white/[0.08] hover:foon-gradient hover:text-[#0A0A0C] text-xs font-bold transition"
                               data-i18n="btn_login_to_buy">
                                سجّل الدخول للشراء
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center rounded-[28px] bg-white dark:bg-[#121216] border border-black/[0.06] dark:border-white/[0.07]">
                    <p class="text-sm text-neutral-400 font-semibold" data-i18n="empty_products">لا توجد منتجات معروضة حالياً.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function updateSmmTotal(input) {
            const unitPrice = parseFloat(input.dataset.unitPrice || '0');
            const qty = Math.max(0, parseInt(input.value || '0', 10));
            const raw = (unitPrice * qty) / 1000;
            const total = raw > 0 ? Math.max(0.01, Math.round(raw * 100) / 100) : 0;
            const el = document.getElementById(input.dataset.totalTarget);
            if (el) {
                el.textContent = '$' + total.toFixed(2);
            }
        }

        function filterFoonProducts(type, btn) {
            document.querySelectorAll('.foon-filter-btn').forEach(b => {
                b.className = 'foon-filter-btn px-4 py-1.5 rounded-full text-xs font-semibold text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white transition';
            });
            btn.className = 'foon-filter-btn px-4 py-1.5 rounded-full text-xs font-bold transition bg-white dark:bg-[#1D1D22] text-[#0A0A0C] dark:text-white shadow-sm';

            document.querySelectorAll('.foon-product-card').forEach(card => {
                if (type === 'all' || card.dataset.delivery === type) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function searchFoonProducts(query) {
            const q = (query || '').trim().toLowerCase();
            document.querySelectorAll('.foon-product-card').forEach(card => {
                const name = card.dataset.name || '';
                card.style.display = name.includes(q) ? '' : 'none';
            });
        }
    </script>
</x-layouts.store>
