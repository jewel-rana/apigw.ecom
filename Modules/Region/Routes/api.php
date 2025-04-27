<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Region\Http\Controllers\Api\RegionController;

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

Route::group(['prefix' => 'region'], function() {
    Route::get('/', [RegionController::class, 'region'])->name('api.region.index');
    Route::get('country', [RegionController::class, 'country'])->name('api.region.country');
    Route::get('city', [RegionController::class, 'city'])->name('api.region.city');
});
