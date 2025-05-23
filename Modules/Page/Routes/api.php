<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Page\Http\Controllers\PageController;

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

Route::group(['prefix' => 'cms'], function () {
    Route::get('page/{slug}', [PageController::class, 'show'])->name('api.page.show');
});
