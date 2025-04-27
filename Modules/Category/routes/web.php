<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\App\Http\Controllers\CategoryAttributeController;
use Modules\Category\App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {
    Route::group(['prefix' => 'category'], function() {
        Route::get('suggestion', [CategoryController::class, 'suggestions'])->name('category.suggestion');
        Route::resource('attribute', CategoryAttributeController::class)->except(['show'])->names('category.attribute');
    });

    Route::resource('category', CategoryController::class)->names('category');
});
