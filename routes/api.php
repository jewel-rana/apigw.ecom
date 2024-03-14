<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forgot', [AuthController::class, 'forgot']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('change-password', [AuthController::class, 'changePassword'])
        ->middleware('auth:api');

    Route::group(['prefix' => 'user'], function () {
        Route::post('login', [AuthUserController::class, 'login']);
        Route::post('forgot', [AuthUserController::class, 'forgot']);
        Route::post('verify', [AuthUserController::class, 'verify']);
        Route::post('reset-password', [AuthUserController::class, 'resetPassword']);
        Route::post('change-password', [AuthUserController::class, 'changePassword'])
            ->middleware(['auth:api', 'auth:customers']);
    });

    Route::group(['middleware' => ['auth:api', 'auth:customers']], function () {
        Route::get('logout', [AuthController::class, 'logout'])
            ->middleware('auth:api');
    });
});

/* Auth Routes */
Route::group(['middleware' => ['auth:api', 'auth:customers']], function () {
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('orders', [DashboardController::class, 'orderGraphs']);
        Route::get('customers', [DashboardController::class, 'customerGraphs']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('{id}/action', [UserController::class, 'action']);
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::get('export', [CustomerController::class, 'export']);
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('form', [OrderController::class, 'create']);
        Route::get('export', [OrderController::class, 'export']);
        Route::put('{order}/action', [OrderController::class, 'action']);
    });

    Route::group(['prefix' => 'feedback'], function () {
        Route::post('{feedback}/action', [FeedbackController::class, 'action']);
    });

    Route::group(['prefix' => 'payment'], function() {
        Route::post('verify', [PaymentController::class, 'verify']);
        Route::post('execute', [PaymentController::class, 'execute']);
        Route::post('refund', [PaymentController::class, 'refund']);
    });

    Route::apiResource('customer', CustomerController::class)->except(['destroy']);
    Route::apiResource('role', RoleController::class)->except(['destroy']);
    Route::apiResource('permission', PermissionController::class)->except(['destroy']);
    Route::apiResource('user', UserController::class)->except(['destroy']);
    Route::apiResource('order', OrderController::class)->except(['destroy']);
    Route::apiResource('feedback', FeedbackController::class)->except(['destroy']);
    Route::apiResource('complain', ComplainController::class)->except(['destroy']);
    Route::apiResource('payment', PaymentController::class)->except(['destroy']);
});
