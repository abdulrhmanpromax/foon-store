<?php

use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\StorefrontAuthController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

// Public Storefront
Route::get('/', [StorefrontController::class, 'index'])->name('store.home');

// Customer Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [StorefrontAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StorefrontAuthController::class, 'login']);
    Route::get('/register', [StorefrontAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StorefrontAuthController::class, 'register']);
});

// Customer Dashboard & Purchases
Route::middleware('auth')->group(function () {
    Route::post('/logout', [StorefrontAuthController::class, 'logout'])->name('logout');
    Route::get('/account', [CustomerDashboardController::class, 'account'])->name('customer.account');
    Route::post('/deposit', [CustomerDashboardController::class, 'deposit'])->name('customer.deposit');
    Route::get('/deposit/binance/{trx}', [CustomerDashboardController::class, 'showBinanceDeposit'])->name('customer.deposit.binance');
    Route::post('/deposit/binance/{trx}/verify', [CustomerDashboardController::class, 'verifyBinanceDeposit'])->name('customer.deposit.binance.verify');
    Route::post('/deposit/binance/{trx}/cancel', [CustomerDashboardController::class, 'cancelBinanceDeposit'])->name('customer.deposit.binance.cancel');
    Route::post('/buy/{product}', [CustomerDashboardController::class, 'buy'])->name('customer.buy');
});

// Payment Webhooks
Route::post('/webhooks/lemonsqueezy', [PaymentWebhookController::class, 'handleLemonSqueezy'])
    ->name('webhooks.lemonsqueezy');

Route::post('/webhooks/binance', [PaymentWebhookController::class, 'handleBinance'])
    ->name('webhooks.binance');
