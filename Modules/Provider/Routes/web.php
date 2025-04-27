<?php

use Illuminate\Support\Facades\Route;
use Modules\Provider\Http\Controllers\ProviderController;

Route::prefix('dashboard')->group(function() {
    Route::group(['prefix' => 'provider'], function() {
        Route::get('suggestion', [ProviderController::class, 'suggestion'])->name('provider.suggestion');
        Route::resource('cash', 'ProviderCashController')->names('provider.cash');
        Route::resource('user', 'ProviderUserController')->names('provider.user');
        Route::resource('product', 'ProviderProductController')->names('provider.product');
    });

    Route::resources([
        'provider' => 'ProviderController'
    ]);
});
