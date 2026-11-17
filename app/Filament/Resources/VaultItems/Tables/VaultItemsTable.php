<?php

namespace App\Filament\Resources\VaultItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VaultItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->colors([
                        'success' => 'available',
                        'danger' => 'sold',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'متوفر للبيع',
                        'sold' => 'تم البيع',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('credentials')
                    ->label('البيانات / الكود')
                    ->limit(25)
                    ->copyable(),

                TextColumn::make('order.order_number')
                    ->label('رقم الطلب')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'available' => 'متوفر',
                        'sold' => 'تم البيع',
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
