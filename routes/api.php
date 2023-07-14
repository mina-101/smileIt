<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
Route::post('/auth/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::name('accounts.')->prefix('accounts')->group(function () {
        Route::post('deposit', [AccountController::class, 'deposit'])->name('deposit');
        Route::get('history/{account}', [AccountController::class, 'history'])->name('history');
    });
    Route::apiResource('accounts', AccountController::class)->except('update');
    Route::post('users/admin', [UserController::class, 'createAdmin'])->name('users.admin.store');
    Route::apiResource('users', UserController::class);
});
