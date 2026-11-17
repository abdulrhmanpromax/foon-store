<?php

namespace App\Filament\Resources\VaultItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VaultItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('بيانات الكود أو الحساب')
                    ->schema([
                        Select::make('product_id')
                            ->label('المنتج')
                            ->relationship('product', 'name', fn ($query) => $query->where('delivery_type', 'vault'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('status')
                            ->label('الحالة')
                            ->options([
                                'available' => 'متوفر (Available)',
                                'sold' => 'تم البيع (Sold)',
                            ])
                            ->default('available')
                            ->required(),

                        Select::make('order_id')
                            ->label('رقم الطلب المرتبط')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Textarea::make('credentials')
                            ->label('بيانات الدخول / الكود / الحساب')
                            ->helperText('يتم تشفير هذا النص آلياً داخل قاعدة البيانات عبر Crypt للحفاظ على سريته.')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
