<?php

namespace App\Filament\Reseller\Resources\Orders\Pages;

use App\Filament\Reseller\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
