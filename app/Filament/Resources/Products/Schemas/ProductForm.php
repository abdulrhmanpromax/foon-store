<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('معلومات المنتج الأساسية')
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم المنتج')
                            ->required()
                            ->maxLength(255),

                        Select::make('delivery_type')
                            ->label('طريقة التسليم')
                            ->options([
                                'vault' => 'مخزون مسبق (Vault)',
                                'api' => 'ربط مباشر (API)',
                            ])
                            ->default('vault')
                            ->required()
                            ->live(),

                        Textarea::make('description')
                            ->label('وصف المنتج')
                            ->rows(3)
                            ->columnSpanFull()
                            ->nullable(),
                    ])
                    ->columns(2),

                Section::make('التسعير (سعر الوحدة للمخزون / سعر لكل 1,000 لخدمات API)')
                    ->schema([
                        TextInput::make('price')
                            ->label('سعر العميل')
                            ->numeric()
                            ->prefix('$')
                            ->required(),

                        TextInput::make('reseller_price')
                            ->label('سعر الموزع')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('إعدادات الربط الخارجي والكميات (API)')
                    ->schema([
                        Select::make('api_provider_id')
                            ->label('مزود الـ API')
                            ->relationship('apiProvider', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        TextInput::make('external_service_id')
                            ->label('معرف الخدمة لدى المزود')
                            ->nullable()
                            ->maxLength(255),

                        TextInput::make('min_quantity')
                            ->label('الحد الأدنى للكمية (Min)')
                            ->numeric()
                            ->default(100)
                            ->minValue(1)
                            ->required(),

                        TextInput::make('max_quantity')
                            ->label('الحد الأقصى للكمية (Max)')
                            ->numeric()
                            ->default(60000)
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(2)
                    ->visible(fn ($get): bool => $get('delivery_type') === 'api'),

                Section::make('الحالة')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('المنتج نشط ومتاح للشراء')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
