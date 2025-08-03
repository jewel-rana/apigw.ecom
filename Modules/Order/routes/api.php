<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\App\Http\Controllers\Api\OrderController;
use Modules\Order\Http\Controllers\Api\MyOrderController;
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

Route::post('checkout', [OrderCheckoutController::class, 'checkout'])->name('api.order.checkout');

Route::group(['prefix' => 'my', 'middleware' => ['auth:customer']], function() {
    Route::apiResource('order', MyOrderController::class)->only(['index', 'show']);
});

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:api'], function () {
    Route::apiResource('order', OrderController::class);
});
