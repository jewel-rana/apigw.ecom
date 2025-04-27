<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\RoleController;

Route::group(['prefix' => 'auth', 'middleware' => 'guest'], function() {
    Route::get('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('login', [AuthController::class, 'check'])->name('auth.login.post');

    //SMS - Email OTP Route
    Route::get('2fa', [AuthController::class, 'otp'])->name('auth.2fa');
    Route::post('2fa', [AuthController::class, 'verify'])->name('auth.2fa.post');

    //Authenticator Verify Route
    Route::get('verify', [AuthController::class, 'googleAuthy'])->name('auth.authy');

    //Reset-Password Routes
    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('auth.forgot-password');
    Route::post('reset-otp', [AuthController::class, 'resetOtp'])->name('auth.reset-otp');
    Route::get('get-otp/{token}', [AuthController::class, 'getOtp'])->name('auth.get-otp');
    Route::post('verify-reset-otp', [AuthController::class, 'verifyResetOtp'])->name('auth.verify-reset-otp');
    Route::get('reset-password/{token}', [AuthController::class, 'getResetPassword'])->name('auth.get-reset-password');
    Route::put('reset-password', [AuthController::class, 'resetPassword'])->name('auth.reset-password');
});

Route::prefix('dashboard')
    ->namespace('\Modules\Auth\Http\Controllers')
    ->middleware('auth:web')
    ->group(function () {
        Route::prefix('auth')->group(function () {
            Route::get('profile', [AuthController::class, 'profile'])->name('auth.profile');
            Route::get('change-password', [AuthController::class, 'changePassword'])->name('auth.change-password');
            Route::put('update-password', [AuthController::class, 'updatePassword'])->name('auth.update-password');
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::get('role/suggestion', [RoleController::class, 'suggestions'])->name('role.suggestion');

            Route::resources([
                'user' => 'UserController',
                'role' => 'RoleController',
                'permission' => 'PermissionController'
            ]);
        });
    });
