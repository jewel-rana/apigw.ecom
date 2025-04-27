<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\App\Http\Controllers\PaymentController;
use Modules\Payment\App\Http\Controllers\PaymentIpnController;

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
Route::group(['prefix' => 'ipn'], function() {
    Route::post('fib', [PaymentIpnController::class, 'fib'])->name('ipn.fib');
});
Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {
    Route::resource('payment', PaymentController::class)->names('payment');
});
