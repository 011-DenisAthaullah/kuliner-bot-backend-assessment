<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LogApiController;
use App\Http\Controllers\Api\TwoFAController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\TelegramController;
use Illuminate\Support\Facades\Route;

// ======================
// PUBLIC
// ======================
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('2fa/verify', [TwoFAController::class, 'verify']);
});

// Telegram webhook (HARUS PUBLIC)
Route::post('telegram/webhook', [TelegramController::class, 'webhook']);

// ======================
// PROTECTED (JWT)
// ======================
Route::middleware('auth:api')->group(function () {

    // AUTH
    Route::prefix('auth')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me',       [AuthController::class, 'me']);
    });

    // 2FA
    Route::prefix('2fa')->group(function () {
        Route::post('generate', [TwoFAController::class, 'generate']);
        Route::post('enable',   [TwoFAController::class, 'enable']);
        Route::post('disable',  [TwoFAController::class, 'disable']);
    });

    // RESTAURANT
    Route::get('restaurants/search', [RestaurantController::class, 'search']);
    Route::apiResource('restaurants', RestaurantController::class);

    // MENU
    Route::post('menus', [MenuController::class, 'store']);

    // REVIEW
    Route::post('reviews', [ReviewController::class, 'store']);

    // LOGS
    Route::get('logs', [LogApiController::class, 'index']);
});