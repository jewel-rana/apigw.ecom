<?php

use Illuminate\Support\Facades\Route;
use Modules\Menu\Http\Controllers\Api\MenuController;
use Modules\Menu\Http\Controllers\Api\MenuItemController;

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

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:api'], function() {
    Route::group(['prefix' => 'menu'], function() {
        Route::apiResource('{menu}/item', MenuItemController::class);
    });

   Route::apiResource('menu', MenuController::class);
});
