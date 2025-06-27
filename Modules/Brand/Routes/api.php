<?php

use Illuminate\Support\Facades\Route;
use Modules\Brand\Http\Controllers\Api\BrandController;
use Modules\Brand\Http\Controllers\Api\BrandProductController;

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

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'brand'], function () {
        Route::get('suggestions', [BrandController::class, 'suggestions']);
    });

    Route::apiResource('brand', BrandController::class);
});

Route::group(['prefix' => 'brand'], function () {
    Route::get('/', [BrandProductController::class, 'index']);
    Route::get('suggestions', [BrandController::class, 'suggestions']);
    Route::get('{category}/product', [BrandProductController::class, 'show']);
});
