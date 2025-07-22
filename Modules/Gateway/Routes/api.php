<?php

use Illuminate\Support\Facades\Route;
use Modules\Gateway\Http\Controllers\Api\GatewayController;

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
    Route::group(['prefix' => 'gateway'], function () {
        Route::get('suggestion', [GatewayController::class, 'suggestion'])->name('gateway.suggestion');
    });

    Route::apiResource('gateway', GatewayController::class)->except(['destroy']);
});

Route::group(['prefix' => 'gateway'], function () {
    Route::get('suggestion', [GatewayController::class, 'suggestion'])->name('gateway.suggestion');
});
