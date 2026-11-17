<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الحساب الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('الاسم')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->label('كلمة المرور')
                            ->password()
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),

                        Select::make('role')
                            ->label('الدور والصلاحية')
                            ->options([
                                'customer' => 'عميل (Customer)',
                                'reseller' => 'موزع (Reseller)',
                                'admin' => 'مسؤول (Admin)',
                            ])
                            ->default('customer')
                            ->required(),

                        TextInput::make('balance')
                            ->label('الرصيد')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00)
                            ->required(),

                        TextInput::make('api_key')
                            ->label('مفتاح الربط (API Key)')
                            ->helperText('مفتاح خاص للموزعين للربط المباشر مع واجهة الـ API')
                            ->nullable()
                            ->maxLength(80)
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),
            ]);
    }
}
