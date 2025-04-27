<?php

use Illuminate\Support\Facades\Route;
use Modules\Newsletter\App\Http\Controllers\NewsletterSubscriptionController;

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

Route::group(['prefix' => 'newsletter'], function () {
    Route::post('subscribe', [NewsletterSubscriptionController::class, 'subscribe'])->name('api.newsletter.subscribe');
    Route::post('unsubscribe', [NewsletterSubscriptionController::class, 'unsubscribe'])->name('api.newsletter.unsubscribe');
});
