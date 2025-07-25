<?php

use Illuminate\Support\Facades\Route;
use Modules\Shipping\Http\Controllers\Api\ShippingController;

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

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:api'], function () {
    Route::apiResource('shipping', ShippingController::class);
});

Route::group(['prefix' => 'shipping'], function () {
    Route::get('suggestion', [ShippingController::class, 'suggestion'])->name('gateway.suggestion');
});
