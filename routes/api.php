<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;

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
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('role', RoleController::class);
    Route::apiResource('permission', PermissionController::class);
    Route::apiResource('user', UserController::class);
    Route::apiResource('customer', CustomerController::class);
});
