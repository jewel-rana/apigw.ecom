<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\App\Http\Controllers\Api\CategoryController;
use Modules\Category\Http\Controllers\Api\CategoryProductController;

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
    Route::group(['prefix' => 'category'], function () {
        Route::get('colors', [CategoryController::class, 'colors']);
        Route::get('suggestions', [CategoryController::class, 'suggestions']);
    });
    Route::apiResource('category', CategoryController::class);
});

Route::group(['prefix' => 'category'], function () {
    Route::get('/', [CategoryProductController::class, 'index']);
    Route::get('suggestions', [CategoryController::class, 'suggestions']);
    Route::get('{category}/product', [CategoryProductController::class, 'show']);
});
