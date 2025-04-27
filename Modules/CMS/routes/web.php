<?php

use Illuminate\Support\Facades\Route;
use Modules\CMS\App\Http\Controllers\BannerController;
use Modules\CMS\App\Http\Controllers\BannerItemController;
use Modules\CMS\App\Http\Controllers\CMSController;
use Modules\CMS\App\Http\Controllers\MenuController;
use Modules\CMS\App\Http\Controllers\NewArrivalController;

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
    Route::group(['prefix' => 'cms'], function() {
        Route::group(['prefix' => 'banner'], function() {
            Route::post('{banner}/add', [BannerController::class, 'add'])->name('banner.add');
            Route::get('{banner}/{item}/edit', [BannerItemController::class, 'edit'])->name('banner.item.edit');
            Route::put('{banner}/{item}/edit', [BannerItemController::class, 'update'])->name('banner.item.update');
            Route::delete('media/{banner}/delete', [BannerController::class, 'remove'])->name('banner.media.delete');
        });
        Route::resource('new-arrival', NewArrivalController::class);
        Route::resource('banner', BannerController::class)->names('banner');
    });
});
