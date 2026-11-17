<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'متجر الخدمات الرقمية والأكواد' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('store.home') }}" class="flex items-center gap-2 font-black text-xl text-emerald-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>StoreHub</span>
                </a>
                <nav class="hidden md:flex items-center gap-4 text-sm font-semibold text-gray-600">
                    <a href="{{ route('store.home') }}" class="hover:text-emerald-600 transition">المنتجات والخدمات</a>
                </nav>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('customer.account') }}" class="flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3.5 py-1.5 rounded-full text-sm font-bold border border-emerald-200 hover:bg-emerald-100 transition">
                        <span>الرصيد: ${{ number_format((float) auth()->user()->balance, 2) }}</span>
                    </a>
                    <a href="{{ route('customer.account') }}" class="text-sm font-semibold text-gray-700 hover:text-emerald-600">حسابي</a>
                    @if (auth()->user()->isReseller() || auth()->user()->isAdmin())
                        <a href="{{ url('/reseller') }}" class="text-xs font-bold bg-amber-500 text-white px-2.5 py-1 rounded-lg hover:bg-amber-600 transition">لوحة الموزع</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-gray-400 hover:text-red-500 font-semibold mr-2">خروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-emerald-600 px-3 py-1.5">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl shadow-sm transition">إنشاء حساب</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Alerts -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-semibold flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        <div class="max-w-7xl mx-auto px-4">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} - متجر الخدمات الرقمية والأكواد التلقائية.
        </div>
    </footer>
</body>
</html>
