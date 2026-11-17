<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('تفاصيل عملية الإيداع')
                    ->schema([
                        TextInput::make('trx')
                            ->label('رقم المعاملة (TRX)')
                            ->default(fn (): string => 'DEP-'.strtoupper(bin2hex(random_bytes(5))))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('user_id')
                            ->label('المستخدم')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('amount')
                            ->label('المبلغ')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        Select::make('gateway')
                            ->label('بوابة الدفع')
                            ->options([
                                'lemonsqueezy' => 'Lemon Squeezy',
                                'binance' => 'Binance Pay',
                            ])
                            ->required(),

                        Select::make('status')
                            ->label('حالة الإيداع')
                            ->options([
                                'pending' => 'معلق (Pending)',
                                'paid' => 'مدفوع (Paid)',
                                'failed' => 'فشل (Failed)',
                            ])
                            ->default('pending')
                            ->required(),

                        TextInput::make('gateway_order_id')
                            ->label('معرف الطلب لدى البوابة (Gateway Order ID)')
                            ->nullable()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }
}
