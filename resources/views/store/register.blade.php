<x-layouts.store>
    <div class="min-h-[75vh] flex items-center justify-center py-4 sm:py-10">
        <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-12 rounded-[32px] overflow-hidden bg-white dark:bg-[#121216] border border-black/[0.08] dark:border-white/[0.09] shadow-xl">

            <!-- Foon Brand Identity Showcase Panel (5 Columns) -->
            <div class="lg:col-span-5 foon-gradient text-[#0A0A0C] p-7 sm:p-9 flex flex-col justify-between relative overflow-hidden">
                <svg class="w-28 h-28 absolute -top-4 -left-4 opacity-[0.10] pointer-events-none -rotate-12" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.4.604-.4.959v0c0 .333.277.599.61.58a48.1 48.1 0 005.427-.63 48.05 48.05 0 00.582-4.717.532.532 0 00-.533-.57v0c-.355 0-.676.186-.959.401-.29.221-.634.349-1.003.349-1.035 0-1.875-1.007-1.875-2.25s.84-2.25 1.875-2.25c.37 0 .713.128 1.003.349.283.215.604.401.96.401v0a.656.656 0 00.658-.663 48.422 48.422 0 00-.37-5.36c-1.886.342-3.81.574-5.766.689a.578.578 0 01-.61-.58v0z"/>
                </svg>

                <div class="relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0A0A0C] text-[#F5D020] text-[10px] font-extrabold tracking-wider uppercase" data-i18n="auth_register_badge">
                        عضوية جديدة • FOON ID
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
                    <p class="text-xs font-bold text-[#0A0A0C]/85 leading-relaxed" data-i18n="hero_subtitle">
                        أكواد أصلية، اشتراكات بريميوم، وخدمات تواصل اجتماعي بتنفيذ فوري مؤتمت بالكامل وبأعلى معايير الأمان.
                    </p>
                </div>
            </div>

            <!-- Registration Form Panel (7 Columns) -->
            <div class="lg:col-span-7 p-7 sm:p-10 flex flex-col justify-center">
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#1D1D1F] dark:text-white" data-i18n="auth_register_title">
                        إنشاء حساب جديد
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 mt-1.5 leading-relaxed" data-i18n="auth_register_subtitle">
                        انضم إلى Foon Store واشحن محفظتك واستلم اشتراكاتك وخدماتك الرقمية لحظياً.
                    </p>
                </div>

                <form method="POST" action="{{ url('/register') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-neutral-600 dark:text-neutral-300 mb-1.5" data-i18n="auth_name_label">
                            الاسم الكامل
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            placeholder="Abdulrahman"
                            class="w-full h-12 px-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] text-sm font-medium focus:outline-none focus:border-[#F5D020] transition" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-600 dark:text-neutral-300 mb-1.5" data-i18n="auth_email_label">
                            البريد الإلكتروني
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="name@example.com"
                            class="w-full h-12 px-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] text-sm font-medium focus:outline-none focus:border-[#F5D020] transition" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold text-neutral-600 dark:text-neutral-300 mb-1.5" data-i18n="auth_password_label">
                                كلمة المرور
                            </label>
                            <input type="password" name="password" required
                                placeholder="••••••••"
                                class="w-full h-12 px-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] text-sm font-medium focus:outline-none focus:border-[#F5D020] transition" />
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-neutral-600 dark:text-neutral-300 mb-1.5" data-i18n="auth_confirm_password_label">
                                تأكيد كلمة المرور
                            </label>
                            <input type="password" name="password_confirmation" required
                                placeholder="••••••••"
                                class="w-full h-12 px-4 rounded-2xl bg-[#F5F5F7] dark:bg-[#1C1C22] border border-black/[0.08] dark:border-white/[0.1] text-sm font-medium focus:outline-none focus:border-[#F5D020] transition" />
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full h-12 rounded-2xl foon-gradient text-[#0A0A0C] hover:brightness-95 font-extrabold text-sm shadow-md transition mt-2"
                        data-i18n="auth_register_btn">
                        إنشاء الحساب والبدء فوراً
                    </button>
                </form>

                <div class="mt-6 pt-5 border-t border-black/[0.06] dark:border-white/[0.07] text-center text-xs text-neutral-500 dark:text-neutral-400">
                    <span data-i18n="auth_have_account">لديك حساب بالفعل؟</span>
                    <a href="{{ route('login') }}" class="font-extrabold text-[#0A0A0C] dark:text-[#F5D020] underline underline-offset-4 mx-1" data-i18n="auth_signin_now">
                        سجّل الدخول الآن
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-layouts.store>
