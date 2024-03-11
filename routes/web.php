<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontController::class, 'index']);
Route::get('download', [FrontController::class, 'download']);
Route::group(['prefix' => 'checkout'], function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::get('/paynow/{id}', [CheckoutController::class, 'paynow'])->name('checkout.paynow');
    Route::post('/token', [CheckoutController::class, 'token'])->name('checkout.token');
    Route::post('/create', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/execute', [CheckoutController::class, 'execute'])->name('checkout.execute');
    Route::post('/intend', [CheckoutController::class, 'intend'])->name('checkout.intend');
    Route::get('/success/{string}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/fail', [CheckoutController::class, 'fail'])->name('checkout.fail');
    Route::get('/bkash/complete', [CheckoutController::class, 'complete'])->name('checkout.bkash.complete');
});
