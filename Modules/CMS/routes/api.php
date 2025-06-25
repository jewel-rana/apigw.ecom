<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CMS\App\Http\Controllers\Api\BannerController;
use Modules\CMS\App\Http\Controllers\CMSController;
use Modules\CMS\Http\Controllers\FeatureController;

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

Route::name('api.')->group(function () {
    Route::group(['prefix' => 'cms'], function() {
        Route::get('init', [CMSController::class, 'index'])->name('cms.index');
        Route::get('search', [CMSController::class, 'search'])->name('cms.search');
        Route::get('recommendations', [CMSController::class, 'recommendations'])->name('cms.recommendations');
        Route::get('feature-products/{feature}', [CMSController::class, 'featureProducts'])->name('cms.sectionProducts');
        Route::get('banner', [BannerController::class, 'index'])->name('cms.banner.index');

        Route::resource('feature', FeatureController::class)->only(['index', 'show']);
    });
});
