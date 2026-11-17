<?php

use App\Http\Controllers\Api\ResellerApiController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Middleware\ResellerApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

// Reseller API
Route::prefix('v1')->middleware(ResellerApiKeyMiddleware::class)->group(function () {
    Route::get('/balance', [ResellerApiController::class, 'balance'])->name('api.v1.balance');
    Route::get('/products', [ResellerApiController::class, 'products'])->name('api.v1.products');
    Route::post('/orders', [ResellerApiController::class, 'placeOrder'])->name('api.v1.orders');
});

// Payment Webhooks on /api prefix
Route::post('/webhooks/lemonsqueezy', [PaymentWebhookController::class, 'handleLemonSqueezy']);
Route::post('/webhooks/binance', [PaymentWebhookController::class, 'handleBinance']);
