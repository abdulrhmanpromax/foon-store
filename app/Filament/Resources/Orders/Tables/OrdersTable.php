<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

                TextColumn::make('user.name')
                    ->label('العميل')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('target_input')
                    ->label('الهدف / الرابط')
                    ->limit(25)
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('quantity')
                    ->label('الكمية')
                    ->sortable(),

                TextColumn::make('total_price')
                    ->label('المبلغ')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
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
                    ->label('بيانات التسليم')
                    ->limit(20)
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('external_order_id')
                    ->label('الطلب الخارجي')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('product_id')
                    ->label('المنتج')
                    ->relationship('product', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
