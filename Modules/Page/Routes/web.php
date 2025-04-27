<?php

use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->middleware(['auth', 'web'])->group(function() {
    Route::group(['prefix' => 'cms'], function() {
        Route::resource('page', 'PageController');
    });
});
