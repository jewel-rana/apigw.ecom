<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Provider\Http\Controllers\Api\ProviderAuthController;
use Modules\Provider\Http\Controllers\Api\ProviderController;

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

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('suggestion', [ProviderController::class, 'suggestion']);
        Route::put('{supplier}/action', [ProviderController::class, 'action']);
    });

    Route::apiResource('supplier', ProviderController::class);
});

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function () {
    Route::group(['prefix' => 'supplier'], function () {
        Route::post('login', [ProviderAuthController::class, 'login']);
        Route::post('forgot', [ProviderAuthController::class, 'forgot'])->name('api.auth.forgot');
        Route::post('verify', [ProviderAuthController::class, 'verify'])->name('api.auth.verify');
        Route::post('reset', [ProviderAuthController::class, 'reset'])->name('api.auth.reset');
        Route::post('resend/{reference}', [ProviderAuthController::class, 'resendOtp']);
    });
});

