<?php

namespace App\Filament\Resources\VaultItems;

use App\Filament\Resources\VaultItems\Pages\CreateVaultItem;
use App\Filament\Resources\VaultItems\Pages\EditVaultItem;
use App\Filament\Resources\VaultItems\Pages\ListVaultItems;
use App\Filament\Resources\VaultItems\Schemas\VaultItemForm;
use App\Filament\Resources\VaultItems\Tables\VaultItemsTable;
use App\Models\VaultItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VaultItemResource extends Resource
{
    protected static ?string $model = VaultItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $navigationLabel = 'مخزون الأكواد';

    protected static ?string $modelLabel = 'عنصر مخزون';

    protected static ?string $pluralModelLabel = 'مخزون الأكواد والحسابات';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return VaultItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VaultItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVaultItems::route('/'),
            'create' => CreateVaultItem::route('/create'),
            'edit' => EditVaultItem::route('/{record}/edit'),
        ];
    }
}
