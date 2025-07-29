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
    Route::post('checkout', [OrderCheckoutController::class, 'checkout'])->name('api.order.checkout');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::apiResource('order', OrderController::class);
    });
});
