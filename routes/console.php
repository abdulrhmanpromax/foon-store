<?php

use App\Services\Fulfillment\ApiProviderService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    app(ApiProviderService::class)->syncProcessingOrders();
})->everyMinute()->name('sync-api-orders')->withoutOverlapping();
