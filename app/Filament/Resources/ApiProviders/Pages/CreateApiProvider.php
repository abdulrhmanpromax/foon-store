<?php

namespace App\Filament\Resources\ApiProviders\Pages;

use App\Filament\Resources\ApiProviders\ApiProviderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApiProvider extends CreateRecord
{
    protected static string $resource = ApiProviderResource::class;
}
