<?php

namespace App\Filament\Resources\VaultItems\Pages;

use App\Filament\Resources\VaultItems\VaultItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVaultItem extends EditRecord
{
    protected static string $resource = VaultItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
