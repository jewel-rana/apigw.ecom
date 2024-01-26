<?php

use App\Http\Controllers\AuthController;
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
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('role', RoleController::class);
    Route::resource('administrator', UserController::class);
});
