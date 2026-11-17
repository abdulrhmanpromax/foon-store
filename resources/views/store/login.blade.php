<x-layouts.store>
    <div class="min-h-[72vh] flex items-center justify-center py-4 sm:py-10">
        <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-12 rounded-[32px] overflow-hidden bg-white dark:bg-[#121216] border border-black/[0.08] dark:border-white/[0.09] shadow-xl">

            <!-- Foon Brand Identity Showcase Panel (5 Columns on Desktop, Top Banner on Mobile/iPad) -->
            <div class="lg:col-span-5 foon-gradient text-[#0A0A0C] p-7 sm:p-9 flex flex-col justify-between relative overflow-hidden">
                <!-- Watermark Icons -->
                <svg class="w-28 h-28 absolute -top-4 -left-4 opacity-[0.10] pointer-events-none -rotate-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/>
                </svg>
                <svg class="w-28 h-28 absolute -bottom-6 -right-4 opacity-[0.10] pointer-events-none rotate-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z"/>
                </svg>

                <div class="relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0A0A0C] text-[#F5D020] text-[10px] font-extrabold tracking-wider uppercase" data-i18n="auth_login_badge">
                        بوابة الدخول الآمنة • FOON ID
                    </span>

                    <div class="mt-6">
                        <div class="flex items-center gap-3">
                            <span class="font-black text-3xl tracking-tight font-mono text-[#0A0A0C]">Foon</span>
                            <span class="h-7 w-[2px] bg-[#0A0A0C]"></span>
                            <div class="leading-tight">
                                <span class="block text-sm font-extrabold text-[#0A0A0C]">Foon Store</span>
                                <span class="block text-xs font-bold text-[#0A0A0C]/80" data-i18n="brand_tagline">متجرك المضمون</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-8 lg:mt-12 space-y-2">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#0A0A0C]">
                        <span class="w-5 h-5 rounded-full bg-[#0A0A0C] text-[#F5D020] inline-flex items-center justify-center text-[11px]">✓</span>
                        <span data-i18n="bento_instant_title">تسليم لحظي 24/7</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-[#0A0A0C]">
                        <span class="w-5 h-5 rounded-full bg-[#0A0A0C] text-[#F5D020] inline-flex items-center justify-center text-[11px]">✓</span>
                        <span data-i18n="bento_payment_title">بوابات دفع عالمية</span>
                    </div>
                </div>
            </div>

            <!-- Form Panel (7 Columns) -->
            <div class="lg:col-span-7 p-7 sm:p-10 flex flex-col justify-center">
                <div class="mb-7">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#1D1D1F] dark:text-white" data-i18n="auth_login_title">
                        تسجيل الدخول إلى حسابك
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 mt-1.5 leading-relaxed" data-i18n="auth_login_subtitle">
                        أدخل بريدك الإلكتروني وكلمة المرور للوصول إلى محفظتك وطلباتك الفورية.
                    </p>
                </div>

                <form method="POST" action="{{ url('/login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-neutral-600 dark:text-neutral-300 mb-1.5" data-i18n="auth_email_label">
                            البريد الإلكتروني
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="name@example.com"
                            class="w-full h-12 px-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] text-sm font-medium focus:outline-none focus:border-[#F5D020] transition" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-600 dark:text-neutral-300 mb-1.5" data-i18n="auth_password_label">
                            كلمة المرور
                        </label>
                        <div class="relative">
                            <input type="password" id="login-password" name="password" required
                                placeholder="••••••••"
                                class="w-full h-12 px-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] text-sm font-medium focus:outline-none focus:border-[#F5D020] transition" />
                            <button type="button"
                                onclick="const p=document.getElementById('login-password'); p.type=p.type==='password'?'text':'password';"
                                class="absolute inset-y-0 left-3 rtl:left-3 ltr:right-3 ltr:left-auto flex items-center text-xs font-bold text-neutral-400 hover:text-[#0A0A0C] dark:hover:text-white">
                                👁
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-neutral-600 dark:text-neutral-400">
                            <input type="checkbox" name="remember" checked
                                class="w-4 h-4 rounded border-neutral-300 text-[#0A0A0C] focus:ring-[#F5D020]" />
                            <span data-i18n="auth_remember_me">تذكر دخولي على هذا الجهاز</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full h-12 rounded-2xl foon-gradient text-[#0A0A0C] hover:brightness-95 font-extrabold text-sm shadow-md transition mt-2"
                        data-i18n="auth_login_btn">
                        تسجيل الدخول
                    </button>
                </form>

                <div class="mt-7 pt-6 border-t border-black/[0.06] dark:border-white/[0.07] text-center text-xs text-neutral-500 dark:text-neutral-400">
                    <span data-i18n="auth_no_account">ليس لديك حساب في Foon Store؟</span>
                    <a href="{{ route('register') }}" class="font-extrabold text-[#0A0A0C] dark:text-[#F5D020] underline underline-offset-4 mx-1" data-i18n="auth_create_now">
                        أنشئ حساباً جديداً
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-layouts.store>
