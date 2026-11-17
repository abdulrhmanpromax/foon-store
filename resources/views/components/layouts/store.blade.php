<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Foon Store | متجرك المضمون</title>
    @if (file_exists(public_path('images/logo-icon.png')))
        <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">
    @endif

    <!-- Fonts: IBM Plex Sans Arabic & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        foon: {
                            yellow: '#F5D020',
                            lightYellow: '#FFE14D',
                            deepGold: '#DFB400',
                            dark: '#0A0A0C',
                            cardDark: '#121216',
                            light: '#F5F5F7',
                            ink: '#1D1D1F'
                        }
                    },
                    fontFamily: {
                        sans: ['"IBM Plex Sans Arabic"', '"Inter"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        mono: ['"Inter"', 'monospace']
                    }
                }
            }
        };

        (function () {
            const savedTheme = localStorage.getItem('foon_theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            const savedLang = localStorage.getItem('foon_lang') || 'ar';
            document.documentElement.lang = savedLang;
            document.documentElement.dir = savedLang === 'en' ? 'ltr' : 'rtl';
        })();
    </script>

    <style>
        body {
            font-family: 'IBM Plex Sans Arabic', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .tabular-nums {
            font-variant-numeric: tabular-nums;
            font-family: 'Inter', 'IBM Plex Sans Arabic', sans-serif;
        }
        .apple-glass {
            backdrop-filter: saturate(180%) blur(22px);
            -webkit-backdrop-filter: saturate(180%) blur(22px);
        }
        .foon-gradient {
            background: linear-gradient(135deg, #FFE252 0%, #F5D020 52%, #E0B600 100%);
        }
        .foon-dark-gradient {
            background: radial-gradient(circle at top right, rgba(245, 208, 32, 0.16), transparent 55%), linear-gradient(145deg, #16161C 0%, #0A0A0C 100%);
        }
        .bento-card {
            transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
        }
        @media (min-width: 1024px) {
            .bento-card:hover {
                transform: translateY(-3px);
            }
        }
    </style>
</head>
<body class="bg-[#F5F5F7] dark:bg-[#09090B] text-[#1D1D1F] dark:text-[#F5F5F7] min-h-screen flex flex-col selection:bg-[#F5D020] selection:text-[#0A0A0C] transition-colors duration-300 relative overflow-x-hidden">

    <!-- Subtle Ambient Brand Glow -->
    <div class="pointer-events-none fixed -top-40 left-1/2 -translate-x-1/2 w-[650px] h-[260px] rounded-full bg-[#F5D020]/10 dark:bg-[#F5D020]/[0.06] blur-3xl -z-10"></div>

    <!-- Apple Frosted Glass Navbar -->
    <header class="sticky top-0 z-50 apple-glass bg-white/80 dark:bg-[#09090B]/80 border-b border-black/[0.07] dark:border-white/[0.08]">
        <div class="max-w-7xl mx-auto px-3.5 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-2">

            <!-- Brand Identity Lockup -->
            <div class="flex items-center gap-4 lg:gap-7 min-w-0">
                <a href="{{ route('store.home') }}" class="flex items-center gap-2.5 sm:gap-3 group focus:outline-none shrink-0">
                    @if (file_exists(public_path('images/logo-full.png')))
                        <img src="{{ asset('images/logo-full.png') }}"
                             alt="Foon Store | متجرك المضمون"
                             class="h-8 sm:h-10 w-auto object-contain dark:invert dark:brightness-200 transition-transform duration-200 group-hover:scale-[1.02]" />
                    @else
                        <!-- High-Precision Vector Replica of Foon Infinity Logo -->
                        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl foon-gradient flex items-center justify-center shadow-sm border border-black/10 group-hover:scale-105 transition-transform shrink-0">
                            <svg viewBox="0 0 120 48" class="w-8 sm:w-9 h-auto text-[#0A0A0C]" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 38V17C10 12.0294 14.0294 8 19 8H30" stroke="currentColor" stroke-width="7.5" stroke-linecap="round"/>
                                <path d="M10 24H25" stroke="currentColor" stroke-width="7.5" stroke-linecap="round"/>
                                <path d="M55.5 18.5C53.2 15.2 49.4 13 45 13C37.8 13 32 18.8 32 26C32 33.2 37.8 39 45 39C50.2 39 54.5 35.8 57.5 30L60.5 22C63.5 16.2 67.8 13 73 13C80.2 13 86 18.8 86 26C86 33.2 80.2 39 73 39C68.6 39 64.8 36.8 62.5 33.5" stroke="currentColor" stroke-width="7" stroke-linecap="round"/>
                                <path d="M94 38V23C94 17.4772 98.4772 13 104 13C109.523 13 114 17.4772 114 23V38" stroke="currentColor" stroke-width="7.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-2.5">
                            <span class="font-black text-xl sm:text-2xl tracking-tight text-[#0A0A0C] dark:text-white font-mono">Foon</span>
                            <span class="h-5 w-[1.5px] bg-black/25 dark:bg-white/25"></span>
                            <div class="leading-tight">
                                <span class="block text-[11px] sm:text-xs font-extrabold tracking-wide text-[#0A0A0C] dark:text-white">Foon Store</span>
                                <span class="block text-[10px] sm:text-[11px] font-semibold text-neutral-500 dark:text-neutral-400" data-i18n="brand_tagline">متجرك المضمون</span>
                            </div>
                        </div>
                    @endif
                </a>

                <!-- Desktop & iPad Navigation Links -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('store.home') }}"
                       class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ request()->routeIs('store.home') ? 'foon-gradient text-[#0A0A0C] shadow-xs' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white' }}"
                       data-i18n="nav_catalog">
                        المنتجات والخدمات
                    </a>
                    @auth
                        <a href="{{ route('customer.account') }}"
                           class="px-3.5 py-1.5 rounded-full text-xs font-bold transition {{ request()->routeIs('customer.account') || request()->routeIs('customer.deposit.*') ? 'foon-gradient text-[#0A0A0C] shadow-xs' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white' }}"
                           data-i18n="nav_wallet">
                            المحفظة وطلباتي
                        </a>
                    @endauth
                </nav>
            </div>

            <!-- Controls & User Actions -->
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                <!-- Language Switcher (AR / EN) -->
                <button type="button" onclick="toggleFoonLanguage()"
                    class="h-9 px-2.5 sm:px-3 rounded-full bg-black/[0.05] dark:bg-white/[0.08] hover:bg-black/[0.1] dark:hover:bg-white/[0.14] text-[11px] sm:text-xs font-extrabold tracking-wide flex items-center gap-1 transition"
                    title="Switch Language">
                    <svg class="w-3.5 h-3.5 opacity-75 hidden xs:inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582"/>
                    </svg>
                    <span id="lang-toggle-label">EN</span>
                </button>

                <!-- Day / Night Theme Switcher -->
                <button type="button" onclick="toggleFoonTheme()"
                    class="w-9 h-9 rounded-full bg-black/[0.05] dark:bg-white/[0.08] hover:bg-black/[0.1] dark:hover:bg-white/[0.14] flex items-center justify-center transition"
                    title="تبديل المظهر النهاري والليلي">
                    <svg class="w-4 h-4 hidden dark:block text-[#F5D020]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                    </svg>
                    <svg class="w-4 h-4 block dark:hidden text-neutral-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                    </svg>
                </button>

                @auth
                    <!-- Wallet Balance Pill -->
                    <a href="{{ route('customer.account') }}"
                       class="h-9 px-3 sm:px-3.5 rounded-full foon-gradient text-[#0A0A0C] font-bold text-xs flex items-center gap-1 shadow-xs hover:brightness-95 transition">
                        <span class="hidden sm:inline" data-i18n="nav_balance">الرصيد:</span>
                        <span class="tabular-nums font-black">${{ number_format((float) auth()->user()->balance, 2) }}</span>
                    </a>

                    @if (auth()->user()->isAdmin())
                        <a href="/admin"
                           class="hidden lg:inline-flex h-9 px-3.5 items-center rounded-full bg-[#0A0A0C] dark:bg-white text-white dark:text-[#0A0A0C] text-xs font-bold transition">
                            <span data-i18n="nav_admin">لوحة الإدارة</span>
                        </a>
                    @endif

                    @if (auth()->user()->isReseller() || auth()->user()->isAdmin())
                        <a href="/reseller"
                           class="hidden xl:inline-flex h-9 px-3.5 items-center rounded-full border border-black/15 dark:border-white/15 text-xs font-semibold hover:bg-black/5 dark:hover:bg-white/5 transition">
                            <span data-i18n="nav_reseller">لوحة الموزع</span>
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="h-9 px-2.5 sm:px-3 rounded-full text-xs font-semibold text-neutral-500 hover:text-red-600 dark:hover:text-red-400 transition"
                            data-i18n="nav_logout">
                            خروج
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="h-9 px-3 sm:px-4 rounded-full bg-black/[0.05] dark:bg-white/[0.08] hover:bg-black/[0.1] dark:hover:bg-white/[0.14] text-xs font-bold flex items-center transition"
                       data-i18n="nav_login">
                        دخول
                    </a>
                    <a href="{{ route('register') }}"
                       class="h-9 px-3 sm:px-4 rounded-full foon-gradient text-[#0A0A0C] hover:brightness-95 text-xs font-extrabold flex items-center shadow-xs transition"
                       data-i18n="nav_register">
                        حساب جديد
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile & Small Tablet Segmented Bar -->
        <div class="md:hidden border-t border-black/[0.05] dark:border-white/[0.06] px-3.5 py-2 flex items-center justify-between gap-2 bg-white/60 dark:bg-[#09090B]/60">
            <div class="flex items-center gap-1.5 w-full">
                <a href="{{ route('store.home') }}"
                   class="flex-1 text-center py-1.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('store.home') ? 'foon-gradient text-[#0A0A0C] shadow-xs' : 'bg-black/[0.04] dark:bg-white/[0.06] text-neutral-600 dark:text-neutral-300' }}"
                   data-i18n="nav_catalog">
                    المنتجات والخدمات
                </a>
                @auth
                    <a href="{{ route('customer.account') }}"
                       class="flex-1 text-center py-1.5 rounded-xl text-xs font-bold transition {{ request()->routeIs('customer.account') || request()->routeIs('customer.deposit.*') ? 'foon-gradient text-[#0A0A0C] shadow-xs' : 'bg-black/[0.04] dark:bg-white/[0.06] text-neutral-600 dark:text-neutral-300' }}"
                       data-i18n="nav_wallet">
                        المحفظة وطلباتي
                    </a>
                    @if (auth()->user()->isAdmin())
                        <a href="/admin"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold bg-[#0A0A0C] dark:bg-white text-white dark:text-[#0A0A0C]"
                           data-i18n="nav_admin">
                            الإدارة
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-3.5 sm:px-6 lg:px-8 py-6 sm:py-10">
        @if (session('success'))
            <div class="mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 px-4 sm:px-5 py-3.5 flex items-center justify-between text-emerald-800 dark:text-emerald-300 text-xs sm:text-sm font-semibold shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs opacity-60 hover:opacity-100 px-1">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-500/25 px-4 sm:px-5 py-3.5 flex items-center justify-between text-red-800 dark:text-red-300 text-xs sm:text-sm font-semibold shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs opacity-60 hover:opacity-100 px-1">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-500/25 px-4 sm:px-5 py-3.5 text-red-800 dark:text-red-300 text-xs sm:text-sm font-semibold shadow-xs">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Minimal Apple-Style Footer -->
    <footer class="border-t border-black/[0.06] dark:border-white/[0.07] bg-white/70 dark:bg-[#09090B]/70 py-7 mt-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500 dark:text-neutral-400">
            <div class="flex items-center gap-2.5">
                <span class="inline-block w-2.5 h-2.5 rounded-full foon-gradient"></span>
                <span class="font-extrabold text-[#0A0A0C] dark:text-white">Foon Store | متجرك المضمون</span>
                <span>—</span>
                <span data-i18n="footer_rights">جميع الحقوق محفوظة © {{ date('Y') }}</span>
            </div>
            <div class="flex items-center gap-3 text-[11px] font-medium">
                <span data-i18n="footer_security">دفع مشفر وآمن 100%</span>
                <span>•</span>
                <span>Apple Pay / Visa / Mastercard / USDT</span>
            </div>
        </div>
    </footer>

    <!-- Instant Bilingual Dictionary & Theme Script -->
    <script>
        const FOON_I18N = {
            ar: {
                brand_tagline: 'متجرك المضمون',
                nav_catalog: 'المنتجات والخدمات',
                nav_wallet: 'المحفظة وطلباتي',
                nav_balance: 'الرصيد:',
                nav_my_account: 'حسابي',
                nav_admin: 'لوحة الإدارة',
                nav_reseller: 'لوحة الموزع',
                nav_logout: 'خروج',
                nav_login: 'دخول',
                nav_register: 'حساب جديد',
                hero_badge: 'FOON STORE • تسليم رقمي فوري 24/7',
                hero_title: 'متجرك المضمون للاشتراكات والخدمات الرقمية.',
                hero_subtitle: 'أكواد أصلية، اشتراكات بريميوم، وخدمات تواصل اجتماعي بتنفيذ فوري مؤتمت بالكامل وبأعلى معايير الأمان.',
                hero_cta_guest: 'ابدأ الآن واشحن محفظتك',
                hero_cta_auth: 'إدارة المحفظة والرصيد',
                bento_instant_title: 'تسليم لحظي 24/7',
                bento_instant_desc: 'سحب تلقائي من الخزنة أو إرسال فوري عبر API.',
                bento_payment_title: 'بوابات دفع عالمية',
                bento_payment_desc: 'شحن فوري عبر Apple Pay وVisa وMastercard وUSDT.',
                section_products: 'المنتجات والخدمات المتاحة',
                filter_all: 'الكل',
                filter_vault: 'تسليم فوري (مخزون)',
                filter_api: 'تنفيذ مباشر (API)',
                search_placeholder: 'ابحث عن اشتراك أو خدمة...',
                badge_vault: 'تسليم فوري • مخزون',
                badge_api: 'تنفيذ مباشر • API',
                label_available: 'متوفر:',
                label_limits: 'الحد:',
                label_price_unit: 'السعر',
                label_price_1k: 'السعر لكل 1,000',
                label_total_due: 'الإجمالي المطلوب',
                placeholder_target: 'الرابط أو الحساب المطلوب...',
                placeholder_qty: 'الكمية',
                btn_buy_now: 'شراء الآن',
                btn_login_to_buy: 'سجّل الدخول للشراء',
                empty_products: 'لا توجد منتجات مطابقة حالياً.',
                wallet_card_label: 'رصيد المحفظة المتاح',
                wallet_topup_title: 'شحن الرصيد الفوري',
                wallet_amount_label: 'المبلغ بالدولار ($)',
                wallet_gateway_label: 'بوابة الدفع',
                wallet_btn_pay: 'متابعة الدفع الآمن',
                orders_title: 'طلباتي ومشترياتي السابقة',
                col_order_no: 'رقم الطلب',
                col_product: 'المنتج',
                col_qty: 'الكمية',
                col_total: 'الإجمالي',
                col_status: 'الحالة',
                col_delivery: 'الكود / بيانات التسليم',
                col_date: 'التاريخ',
                status_completed: 'مكتمل',
                status_processing: 'قيد التنفيذ',
                status_pending: 'قيد الانتظار',
                status_failed: 'ملغي / مسترد',
                auth_login_badge: 'بوابة الدخول الآمنة • FOON ID',
                auth_login_title: 'تسجيل الدخول إلى حسابك',
                auth_login_subtitle: 'أدخل بريدك الإلكتروني وكلمة المرور للوصول إلى محفظتك وطلباتك الفورية.',
                auth_email_label: 'البريد الإلكتروني',
                auth_password_label: 'كلمة المرور',
                auth_remember_me: 'تذكر دخولي على هذا الجهاز',
                auth_login_btn: 'تسجيل الدخول',
                auth_no_account: 'ليس لديك حساب في Foon Store؟',
                auth_create_now: 'أنشئ حساباً جديداً',
                auth_register_badge: 'عضوية جديدة • FOON ID',
                auth_register_title: 'إنشاء حساب جديد',
                auth_register_subtitle: 'انضم إلى Foon Store واشحن محفظتك واستلم اشتراكاتك وخدماتك الرقمية لحظياً.',
                auth_name_label: 'الاسم الكامل',
                auth_confirm_password_label: 'تأكيد كلمة المرور',
                auth_register_btn: 'إنشاء الحساب والبدء فوراً',
                auth_have_account: 'لديك حساب بالفعل؟',
                auth_signin_now: 'سجّل الدخول الآن',
                binance_badge: 'دفع رقمي فوري • BINANCE PAY USDT',
                binance_title: 'إتمام الشحن عبر Binance Pay',
                binance_subtitle: 'حوّل المبلغ المطلوب بدقة عبر تطبيق بينانس ثم الصق رقم الطلب (Order ID) للتأكيد الفوري.',
                binance_amount_label: 'المبلغ المطلوب تحويله (USDT)',
                binance_merchant_label: 'معرف الدفع (Binance Pay ID)',
                binance_account_label: 'اسم الحساب المستلم',
                binance_copy_btn: 'نسخ',
                binance_step1: '1. افتح تطبيق Binance واختر Binance Pay ثم إرسال (Send).',
                binance_step2: '2. الصق معرف الدفع أعلاه وأرسل المبلغ المطلوب بعملة USDT.',
                binance_step3: '3. انسخ رقم عملية الدفع (Order ID) من الإيصال والصقه في الحقل المقابل.',
                binance_timer_label: 'الوقت المتبقي لصلاحية الطلب',
                binance_attempts_label: 'المحاولات المتبقية للتحقق:',
                binance_receipt_label: 'رقم عملية الدفع / كود الإيصال (Order ID)',
                binance_receipt_placeholder: 'مثال: 294810485910293847',
                binance_verify_btn: 'تأكيد الإيصال وشحن الرصيد فوراً',
                binance_cancel_btn: 'إلغاء طلب الشحن والعودة للمحفظة',
                binance_expired_msg: 'انتهت مهلة طلب الشحن هذا. يرجى العودة للمحفظة وإنشاء طلب جديد.',
                footer_rights: 'جميع الحقوق محفوظة © {{ date('Y') }}',
                footer_security: 'دفع مشفر وآمن 100%'
            },
            en: {
                brand_tagline: 'Your Trusted Store',
                nav_catalog: 'Store & Services',
                nav_wallet: 'Wallet & Orders',
                nav_balance: 'Balance:',
                nav_my_account: 'Account',
                nav_admin: 'Admin Panel',
                nav_reseller: 'Reseller Hub',
                nav_logout: 'Sign out',
                nav_login: 'Sign in',
                nav_register: 'Get Started',
                hero_badge: 'FOON STORE • 24/7 INSTANT DIGITAL DELIVERY',
                hero_title: 'Your trusted store for digital subscriptions & SMM.',
                hero_subtitle: 'Genuine license keys, premium subscriptions, and automated social media services delivered in seconds.',
                hero_cta_guest: 'Create Account & Top Up',
                hero_cta_auth: 'Manage Wallet & Orders',
                bento_instant_title: 'Instant 24/7 Delivery',
                bento_instant_desc: 'Automated vault key claim & real-time API dispatch.',
                bento_payment_title: 'Global Payment Gateways',
                bento_payment_desc: 'Instant top-up via Apple Pay, Visa, Mastercard, and USDT.',
                section_products: 'Available Products & Services',
                filter_all: 'All Items',
                filter_vault: 'Instant Vault',
                filter_api: 'Direct API (SMM)',
                search_placeholder: 'Search subscriptions or services...',
                badge_vault: 'Instant Vault',
                badge_api: 'Direct API',
                label_available: 'In stock:',
                label_limits: 'Limits:',
                label_price_unit: 'Price',
                label_price_1k: 'Price per 1,000',
                label_total_due: 'Total Due',
                placeholder_target: 'Target Link or Username...',
                placeholder_qty: 'Quantity',
                btn_buy_now: 'Buy Now',
                btn_login_to_buy: 'Sign in to Purchase',
                empty_products: 'No matching products found.',
                wallet_card_label: 'Available Wallet Balance',
                wallet_topup_title: 'Instant Wallet Top-Up',
                wallet_amount_label: 'Amount in USD ($)',
                wallet_gateway_label: 'Payment Gateway',
                wallet_btn_pay: 'Proceed to Secure Checkout',
                orders_title: 'Order & Purchase History',
                col_order_no: 'Order ID',
                col_product: 'Product',
                col_qty: 'Qty',
                col_total: 'Total',
                col_status: 'Status',
                col_delivery: 'Delivery Code / Status',
                col_date: 'Date',
                status_completed: 'Completed',
                status_processing: 'Processing',
                status_pending: 'Pending',
                status_failed: 'Refunded / Failed',
                auth_login_badge: 'SECURE ACCESS • FOON ID',
                auth_login_title: 'Sign in to Foon Store',
                auth_login_subtitle: 'Enter your email and password to access your wallet and instant digital orders.',
                auth_email_label: 'Email Address',
                auth_password_label: 'Password',
                auth_remember_me: 'Keep me signed in on this device',
                auth_login_btn: 'Sign In',
                auth_no_account: 'New to Foon Store?',
                auth_create_now: 'Create an account',
                auth_register_badge: 'NEW ACCOUNT • FOON ID',
                auth_register_title: 'Create your Foon ID',
                auth_register_subtitle: 'Join Foon Store to top up your wallet and receive digital keys & SMM services instantly.',
                auth_name_label: 'Full Name',
                auth_confirm_password_label: 'Confirm Password',
                auth_register_btn: 'Create Account & Continue',
                auth_have_account: 'Already have an account?',
                auth_signin_now: 'Sign in now',
                binance_badge: 'INSTANT CRYPTO CHECKOUT • BINANCE PAY',
                binance_title: 'Complete USDT Top-Up via Binance Pay',
                binance_subtitle: 'Send the exact USDT amount via Binance Pay, then paste your Order ID for instant automated credit.',
                binance_amount_label: 'Exact Amount to Send (USDT)',
                binance_merchant_label: 'Merchant Binance Pay ID',
                binance_account_label: 'Recipient Account Name',
                binance_copy_btn: 'Copy',
                binance_step1: '1. Open the Binance app, tap Binance Pay, and select Send.',
                binance_step2: '2. Paste the Merchant Pay ID above and transfer the exact amount in USDT.',
                binance_step3: '3. Copy the Order ID from your payment receipt and paste it below to verify.',
                binance_timer_label: 'Session Expiration Timer',
                binance_attempts_label: 'Remaining verification attempts:',
                binance_receipt_label: 'Binance Payment Order ID / Receipt Code',
                binance_receipt_placeholder: 'e.g. 294810485910293847',
                binance_verify_btn: 'Verify Receipt & Credit Wallet Now',
                binance_cancel_btn: 'Cancel Top-Up & Return to Wallet',
                binance_expired_msg: 'This top-up session has expired. Please return to your wallet and start a new deposit.',
                footer_rights: 'All rights reserved © {{ date('Y') }}',
                footer_security: '100% Encrypted Checkout'
            }
        };

        function applyFoonLanguage(lang) {
            const dict = FOON_I18N[lang] || FOON_I18N.ar;
            document.documentElement.lang = lang;
            document.documentElement.dir = lang === 'en' ? 'ltr' : 'rtl';
            localStorage.setItem('foon_lang', lang);

            const toggleLabel = document.getElementById('lang-toggle-label');
            if (toggleLabel) {
                toggleLabel.textContent = lang === 'ar' ? 'EN' : 'عربي';
            }

            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (dict[key]) el.textContent = dict[key];
            });

            document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                const key = el.getAttribute('data-i18n-placeholder');
                if (dict[key]) el.setAttribute('placeholder', dict[key]);
            });
        }

        function toggleFoonLanguage() {
            const current = localStorage.getItem('foon_lang') || 'ar';
            applyFoonLanguage(current === 'ar' ? 'en' : 'ar');
        }

        function toggleFoonTheme() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('foon_theme', isDark ? 'dark' : 'light');
        }

        function copyFoonText(text, btn) {
            navigator.clipboard.writeText(text);
            const original = btn.innerHTML;
            btn.textContent = '✓';
            setTimeout(() => { btn.innerHTML = original; }, 1400);
        }

        document.addEventListener('DOMContentLoaded', () => {
            applyFoonLanguage(localStorage.getItem('foon_lang') || 'ar');
        });
    </script>
</body>
</html>
