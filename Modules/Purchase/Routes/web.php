<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\PurchaseController;
use Modules\Purchase\Http\Controllers\PurchaseItemController;

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

Route::group(['middleware'=>'auth','prefix'=>'dashboard'], function () {
    Route::group(['prefix' => 'purchase'], function () {
        Route::get('suggestion', [PurchaseController::class, 'suggestions'])->name('purchase.suggestion');
        Route::get('item/suggestion', [PurchaseItemController::class, 'suggestions'])->name('purchase.item.suggestion');
        Route::resource('item', PurchaseItemController::class)->names('purchase.item');
    });
    Route::resource('purchase', PurchaseController::class);
});
