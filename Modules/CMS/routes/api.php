<?php

use Illuminate\Support\Facades\Route;
use Modules\CMS\App\Http\Controllers\Api\BannerController;
use Modules\CMS\App\Http\Controllers\Api\BannerItemController;
use Modules\CMS\App\Http\Controllers\Api\HomeCardController;
use Modules\CMS\App\Http\Controllers\CMSController;
use \Modules\CMS\App\Http\Controllers\Api\FeatureProductController;

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
        Route::get('feature-product/{feature}', [CMSController::class, 'featureProducts'])->name('cms.sectionProducts');
        Route::get('banner', [BannerController::class, 'index'])->name('cms.banner.index');
    });

    Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:api'], function() {
        Route::group(['prefix' => 'banner'], function() {
            Route::apiResource('{banner}/item', BannerItemController::class);
        });

        Route::group(['prefix' => 'feature'], function() {
            Route::apiResource('{feature}/product', FeatureProductController::class)->only(['index', 'store', 'destroy']);
        });

        Route::apiResource('home-card', HomeCardController::class);
        Route::apiResource('banner', BannerController::class);
        Route::apiResource('feature', 'FeatureController')->only(['index', 'show', 'store', 'update', 'destroy']);
    });
});
