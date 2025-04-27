<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\App\Http\Controllers\SocialiteController;
use Modules\Auth\Http\Controllers\Api\AuthController;
use Modules\Auth\Http\Controllers\Api\ProfileController;
use Modules\Auth\Http\Controllers\Api\RegisterController;

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

Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => 'guest'], function() {
        Route::post('register', [RegisterController::class, 'register'])->name('api.auth.register');
        Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
        Route::post('forgot', [AuthController::class, 'forgot'])->name('api.auth.forgot');
        Route::post('verify', [AuthController::class, 'verify'])->name('api.auth.verify');
        Route::post('reset', [AuthController::class, 'reset'])->name('api.auth.reset');
        Route::post('resend/{reference}', [AuthController::class, 'resendOtp']);
    });

    Route::group(['prefix' => 'socialite', 'middleware' => 'guest'], function () {
        Route::post('/', [SocialiteController::class, 'index'])->name('api.auth.socialite');
        Route::get('info/{profileId}', [SocialiteController::class, 'info'])->name('api.auth.socialite.info');
        Route::post('update', [SocialiteController::class, 'update'])->name('api.auth.socialite.update');
        Route::delete('del/{customer}', [SocialiteController::class, 'destroy'])->name('api.auth.socialite.delete');
    });

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('profile', [ProfileController::class, 'index'])->name('api.auth.profile');
        Route::post('profile', [ProfileController::class, 'update'])->name('api.auth.profile.update');
        Route::post('password', [AuthController::class, 'changePassword'])->name('api.auth.password');
        Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    });
});
