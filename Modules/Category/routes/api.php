<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\App\Http\Controllers\Api\CategoryController;

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

Route::group(['prefix' => 'category'], function () {
    Route::get('{category}/show', [CategoryController::class, 'show'])->name('api.category.show');
});
Route::apiResource('category', CategoryController::class)
    ->names('api.category')
    ->only(['index']);
