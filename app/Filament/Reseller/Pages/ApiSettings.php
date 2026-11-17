<?php

namespace App\Filament\Reseller\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ApiSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracket;

    protected static ?string $navigationLabel = 'إعدادات API';

    protected static ?string $title = 'إعدادات ووثائق الـ API للموزعين';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.reseller.pages.api-settings';

    public function generateApiKey(): void
    {
        $user = auth()->user();
        $user->api_key = 'res_'.bin2hex(random_bytes(20));
        $user->save();

        Notification::make()
            ->title('تم توليد مفتاح API جديد بنجاح')
            ->success()
            ->send();
    }
}
