<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\App\Http\Controllers\OrderController;
use Modules\Order\App\Http\Controllers\OrderRefundController;
use Modules\Order\App\Http\Controllers\OrderSoldProductController;

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
    Route::group(['prefix' => 'order'], function() {
        Route::get('sold-products', [OrderController::class, 'sold'])->name('order.sold');
        Route::get('sold-products-export', [OrderController::class, 'export_sold'])->name('order.sold.export');
        Route::get('export', [OrderController::class, 'export'])->name('order.export');
        Route::get('refund/export', [OrderRefundController::class, 'export'])->name('refund.export');
        Route::resource('refund', OrderRefundController::class);
    });

    Route::resource('order', OrderController::class)->names('order');
});
