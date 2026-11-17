<?php

namespace App\Filament\Resources\Deposits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DepositsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('trx')
                    ->label('رقم المعاملة')
                    ->searchable()
                    ->copyable()
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('المستخدم')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('المبلغ')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('gateway')
                    ->label('بوابة الدفع')
                    ->badge()
                    ->colors([
                        'warning' => 'binance',
                        'info' => 'lemonsqueezy',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'lemonsqueezy' => 'Lemon Squeezy',
                        'binance' => 'Binance Pay',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'معلق',
                        'paid' => 'مدفوع',
                        'failed' => 'فشل',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('gateway_order_id')
                    ->label('معرف البوابة')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('تاريخ العملية')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('gateway')
                    ->label('بوابة الدفع')
                    ->options([
                        'lemonsqueezy' => 'Lemon Squeezy',
                        'binance' => 'Binance Pay',
                    ]),
                SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'معلق',
                        'paid' => 'مدفوع',
                        'failed' => 'فشل',
                    ]),
                SelectFilter::make('user_id')
                    ->label('المستخدم')
                    ->relationship('user', 'name'),
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
