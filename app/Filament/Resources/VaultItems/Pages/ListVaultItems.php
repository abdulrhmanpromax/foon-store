<?php

namespace App\Filament\Resources\VaultItems\Pages;

use App\Filament\Resources\VaultItems\VaultItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVaultItems extends ListRecords
{
    protected static string $resource = VaultItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
