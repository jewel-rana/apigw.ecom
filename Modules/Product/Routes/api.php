<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Api\MyWishListController;
use Modules\Product\Http\Controllers\Api\ProductController;

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
    Route::group(['prefix' => 'product'], function () {
        Route::delete('{product}/media/{media}', [ProductController::class, 'removeMedia']);
        Route::delete('{product}/wishlist', [ProductController::class, 'removeWishlist']);
        Route::get('suggestions', [ProductController::class, 'suggestions']);
    });

    Route::apiResource('product', ProductController::class);
});

Route::group(['prefix' => 'my', 'middleware' => ['auth:customer']], function () {
    Route::apiResource('wishlist', MyWishListController::class)->only(['index', 'store', 'destroy']);
});
