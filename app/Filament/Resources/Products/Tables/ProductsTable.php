<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('المنتج')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('delivery_type')
                    ->label('طريقة التسليم')
                    ->badge()
                    ->colors([
                        'info' => 'vault',
                        'warning' => 'api',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'vault' => 'مخزون (Vault)',
                        'api' => 'ربط (API)',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('price')
                    ->label('سعر العميل')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('reseller_price')
                    ->label('سعر الموزع')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('available_vault_items_count')
                    ->counts('availableVaultItems')
                    ->label('المخزون المتوفر')
                    ->badge()
                    ->color('success'),

                TextColumn::make('apiProvider.name')
                    ->label('مزود الـ API')
                    ->placeholder('-')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('delivery_type')
                    ->label('طريقة التسليم')
                    ->options([
                        'vault' => 'مخزون مسبق',
                        'api' => 'ربط API',
                    ]),
                TernaryFilter::make('is_active')
                    ->label('الحالة'),
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
