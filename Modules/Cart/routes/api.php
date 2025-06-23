<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\App\Http\Controllers\CartController;

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
    Route::group(['prefix' => 'cart'], function () {
        Route::post('validate', [CartController::class, 'validate'])->name('api.cart.validate');
    });

    Route::apiResource('cart', CartController::class)->names('api.cart');
});
