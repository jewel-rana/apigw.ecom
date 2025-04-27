<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\App\Http\Controllers\CustomerController;

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
    Route::group(['prefix' => 'customer'], function() {
        Route::get('suggestion', [CustomerController::class, 'suggestions'])
            ->name('customer.suggestion');
        Route::put('{customer}/action', [CustomerController::class, 'action'])
            ->name('customer.action');
        Route::get('export', [CustomerController::class, 'export'])
            ->name('customer.export');
        Route::get('order/{customer}/export', [CustomerController::class, 'orderExport'])
            ->name('customer.order.export');
    });
    Route::resource('customer', CustomerController::class)->names('customer');
});
