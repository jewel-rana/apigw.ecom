<?php

use Illuminate\Support\Facades\Route;
use Modules\Region\Http\Controllers\CityController;
use Modules\Region\Http\Controllers\CountryController;
use Modules\Region\Http\Controllers\RegionController;
use Modules\Region\Http\Controllers\TimeZoneController;

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

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function() {
    Route::group(['prefix' => 'region'], function() {
        Route::get('country/suggestion', [CountryController::class, 'suggestion'])
            ->name('country.suggestion');
        Route::get('city/suggestion', [CityController::class, 'suggestion'])
            ->name('city.suggestion');

        Route::get('timezone/suggestion', [TimeZoneController::class, 'suggestion'])
            ->name('timezone.suggestion');

        Route::get('suggestion', [RegionController::class, 'suggestion'])
            ->name('region.suggestion');

        Route::resources([
            'country' => 'CountryController',
            'city' => 'CityController',
            'language' => 'LanguageController',
            'timezone' => 'TimeZoneController'
        ]);
    });

    Route::resource('region', 'RegionController');
});
