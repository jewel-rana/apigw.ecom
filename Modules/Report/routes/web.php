<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\App\Http\Controllers\ReportController;

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

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:web'], function () {
    Route::group(['prefix' => 'report'], function () {
        Route::get('transaction', [ReportController::class, 'transaction'])->name('report.transaction');
        Route::get('order', [ReportController::class, 'order'])->name('report.order');
        Route::get('customer', [ReportController::class, 'customer'])->name('report.customer');

        Route::resource('export', 'ReportExportController')->only(['index', 'destroy'])->names('report.export');
    });

    Route::resource('report', ReportController::class)->only(['index', 'store'])->names('report');
});
