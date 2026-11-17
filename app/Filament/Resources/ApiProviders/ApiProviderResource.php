<?php

namespace App\Filament\Resources\ApiProviders;

use App\Filament\Resources\ApiProviders\Pages\CreateApiProvider;
use App\Filament\Resources\ApiProviders\Pages\EditApiProvider;
use App\Filament\Resources\ApiProviders\Pages\ListApiProviders;
use App\Filament\Resources\ApiProviders\Schemas\ApiProviderForm;
use App\Filament\Resources\ApiProviders\Tables\ApiProvidersTable;
use App\Models\ApiProvider;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApiProviderResource extends Resource
{
    protected static ?string $model = ApiProvider::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedServer;

    protected static ?string $navigationLabel = 'مزودو الـ API';

    protected static ?string $modelLabel = 'مزود API';

    protected static ?string $pluralModelLabel = 'مزودو الـ API';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ApiProviderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiProvidersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApiProviders::route('/'),
            'create' => CreateApiProvider::route('/create'),
            'edit' => EditApiProvider::route('/{record}/edit'),
        ];
    }
}
