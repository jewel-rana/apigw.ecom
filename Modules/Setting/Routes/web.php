<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\SettingController;

Route::prefix('dashboard')->middleware(['auth', 'web'])->group(function() {
    Route::group(['prefix' => 'setting'], function() {
        Route::get('suggestion', [SettingController::class, 'suggestions'])->name('setting.suggestion');
        Route::resource('attribute', 'OptionAttributeController')->only(['index', 'store', 'destroy'])->names('setting.attribute');
    });
    Route::resource('setting', 'SettingController')->only(['index', 'store']);
});
