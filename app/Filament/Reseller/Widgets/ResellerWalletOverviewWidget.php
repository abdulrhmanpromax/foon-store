<?php

namespace App\Filament\Reseller\Widgets;

use App\Models\Order;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResellerWalletOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        $user = auth()->user();

        return [
            Stat::make('رصيد المحفظة الحالي', '$'.number_format((float) ($user?->balance ?? 0), 2))
                ->description('رصيدك المتاح لشراء المنتجات')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('success'),

            Stat::make('إجمالي الطلبات', Order::where('user_id', $user?->id)->count())
                ->description('عدد طلباتك المكتملة والمعالجة')
                ->descriptionIcon(Heroicon::OutlinedShoppingCart)
                ->color('info'),

            Stat::make('نوع الحساب', $user?->isAdmin() ? 'مدير النظام' : 'موزع معتمد (VIP)')
                ->description('أسعار الجملة مفعلة تلقائياً')
                ->descriptionIcon(Heroicon::OutlinedShieldCheck)
                ->color('warning'),
        ];
    }
}
