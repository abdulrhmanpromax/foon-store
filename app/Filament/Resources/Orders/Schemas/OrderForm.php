<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات الطلب الأساسية')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('رقم الطلب')
                            ->default(fn (): string => 'ORD-'.strtoupper(bin2hex(random_bytes(4))))
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('user_id')
                            ->label('المستخدم / العميل')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('product_id')
                            ->label('المنتج')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('target_input')
                            ->label('الهدف / الرابط / الدولة')
                            ->helperText('رابط الحساب لخدمات الرشق أو رمز الدولة للأرقام')
                            ->nullable()
                            ->columnSpanFull(),

                        TextInput::make('quantity')
                            ->label('الكمية')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),

                        TextInput::make('total_price')
                            ->label('المبلغ الإجمالي')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        Select::make('status')
                            ->label('حالة الطلب')
                            ->options([
                                'pending' => 'معلق (Pending)',
                                'processing' => 'قيد المعالجة (Processing)',
                                'completed' => 'مكتمل (Completed)',
                                'failed' => 'فشل (Failed)',
                            ])
                            ->default('pending')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('بيانات التسليم والربط الخارجي')
                    ->schema([
                        TextInput::make('external_order_id')
                            ->label('رقم الطلب لدى المزود الخارجي')
                            ->nullable()
                            ->maxLength(255),

                        Textarea::make('delivery_data')
                            ->label('بيانات التسليم المسلمة للعميل (مشفرة)')
                            ->helperText('يتم تشفير هذه البيانات تلقائياً بواسطة Crypt للحفاظ على سرية الأكواد.')
                            ->rows(3)
                            ->columnSpanFull()
                            ->nullable(),
                    ])
                    ->columns(1),
            ]);
    }
}
