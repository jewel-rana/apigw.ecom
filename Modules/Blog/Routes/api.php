<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\Api\BlogController;

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
    Route::group(['prefix' => 'blog'], function () {
        Route::get('suggestions', [BlogController::class, 'suggestions']);
    });

    Route::apiResource('blog', BlogController::class);
});
