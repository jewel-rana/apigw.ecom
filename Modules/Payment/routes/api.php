<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Payment\App\Http\Controllers\Api\PaymentController;

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

Route::group(['prefix' => 'payment'], function () {
    Route::post('{payment}/{gateway}/ipn', [PaymentController::class, 'ipn'])->name('api.payment.ipn');

    Route::get('{payment}/verify', [PaymentController::class, 'verify'])
        ->middleware('auth:api')
        ->name('api.payment.verify');

    Route::get('{gateway_trx_id}/status', [PaymentController::class, 'checkStatus'])
        ->middleware('auth:api')
        ->name('api.payment.status');

    Route::get('fib/{payment}/verify', [PaymentController::class, 'fibPaymentVerify'])->name('api.fib.payment.verify');
});
Route::apiResource('payment', PaymentController::class)->names('api.payment');
