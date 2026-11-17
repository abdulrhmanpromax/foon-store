<?php

namespace App\Filament\Reseller\Resources\Orders\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('رقم الطلب')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('الكمية')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('المبلغ الإجمالي')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('حالة الطلب')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'معلق',
                        'processing' => 'قيد المعالجة',
                        'completed' => 'مكتمل',
                        'failed' => 'فشل',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('decrypted_preview')
                    ->label('الكود / بيانات التسليم')
                    ->placeholder('قيد المعالجة...')
                    ->copyable()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('تاريخ الطلب')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('حالة الطلب')
                    ->options([
                        'pending' => 'معلق',
                        'processing' => 'قيد المعالجة',
                        'completed' => 'مكتمل',
                        'failed' => 'فشل',
                    ]),
            ]);
    }
}
