<?php

namespace App\Filament\Resources\ApiProviders\Pages;

use App\Filament\Resources\ApiProviders\ApiProviderResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiProviders extends ListRecords
{
    protected static string $resource = ApiProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
