<?php

use Illuminate\Support\Facades\Route;
use Modules\CMS\App\Http\Controllers\Api\BannerController;
use Modules\CMS\App\Http\Controllers\Api\CmsSupplierController;
use Modules\CMS\App\Http\Controllers\Api\FeatureProductController;
use Modules\CMS\App\Http\Controllers\Api\HomeCardController;
use Modules\CMS\App\Http\Controllers\CMSController;
use Modules\Product\Http\Controllers\Api\ProductController;

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
    Route::group(['prefix' => 'cms', 'middleware' => ['guest.cookie']], function() {
        Route::get('init', [CMSController::class, 'index'])->name('cms.index');
        Route::get('search', [CMSController::class, 'search'])->name('cms.search');
        Route::get('recommendations', [CMSController::class, 'recommendations'])->name('cms.recommendations');
        Route::get('feature-product/{feature}', [CMSController::class, 'featureProducts'])->name('cms.sectionProducts');
        Route::get('banner', [BannerController::class, 'cms'])->name('cms.banner.index');
        Route::get('product/{product}', [ProductController::class, 'show'])->name('cms.product.show');

        Route::apiResource('supplier', CmsSupplierController::class)->only(['index', 'show']);
    });

    Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:api'], function() {

        Route::group(['prefix' => 'feature'], function() {
            Route::apiResource('{feature}/product', FeatureProductController::class)->only(['index', 'store', 'destroy']);
        });

        Route::apiResource('home-card', HomeCardController::class);
        Route::apiResource('feature', 'FeatureController')->only(['index', 'show', 'store', 'update', 'destroy']);
    });
});
