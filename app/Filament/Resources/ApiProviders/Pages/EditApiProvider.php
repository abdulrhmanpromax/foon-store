<?php

namespace App\Filament\Resources\ApiProviders\Pages;

use App\Filament\Resources\ApiProviders\ApiProviderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApiProvider extends EditRecord
{
    protected static string $resource = ApiProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
