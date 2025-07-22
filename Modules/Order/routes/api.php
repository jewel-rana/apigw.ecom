<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\App\Http\Controllers\Api\OrderController;
use Modules\Order\Http\Controllers\Api\OrderCheckoutController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::group(['middleware' => 'guest.cookie'], function () {
    Route::get('order/{order}/check', [OrderController::class, 'check'])->name('api.order.check');
    Route::post('checkout', [OrderCheckoutController::class, 'checkout'])->name('api.order.checkout');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'order'], function () {
            Route::get('{item}/payload', [OrderController::class, 'payload'])->name('api.order.payload');
            Route::get('{order}/mint-route', [OrderController::class, 'mintRoute'])->name('api.order.mint-route');
            Route::post('{order}/deliver', [OrderController::class, 'deliver'])->name('api.order.deliver');
        });
        Route::apiResource('order', OrderController::class);
    });
});
