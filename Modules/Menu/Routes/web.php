<?php

use Illuminate\Support\Facades\Route;
use Modules\Menu\Http\Controllers\MenuController;
use Modules\Menu\Http\Controllers\MenuItemController;

Route::prefix('dashboard')->group(function() {
    Route::group(['prefix' => 'cms'], function() {
        Route::group(['prefix' => 'menu'], function () {
            Route::get('icon/suggestion', [MenuController::class, 'iconSuggestions'])->name('menu.icon.suggestion');

            Route::group(['prefix' => 'item'], function() {
                Route::post('item/save', 'MenuItemController@save')->name('menu.item.save');
                Route::post('item/{menu}/add', [MenuItemController::class, 'addItem'])->name('menu.item.add');
                Route::get('item/{menu}/suggestion', [MenuItemController::class, 'suggestions'])->name('menu.item.suggestion');
            });
            Route::resource('item', 'MenuItemController')->only(['index', 'store', 'update', 'destroy'])->names('menu.item');
            Route::resource('attribute', 'MenuAttributeController')->names('menu.attribute');
        });

        Route::resource('menu', 'MenuController');
    });
});
