<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('نوع الحساب')
                    ->badge()
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'reseller',
                        'success' => 'customer',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'مسؤول',
                        'reseller' => 'موزع',
                        'customer' => 'عميل',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('balance')
                    ->label('الرصيد')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('api_key')
                    ->label('API Key')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('نوع الحساب')
                    ->options([
                        'customer' => 'عميل',
                        'reseller' => 'موزع',
                        'admin' => 'مسؤول',
                    ]),
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
