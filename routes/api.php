<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::name('accounts.')->prefix('accounts')->group(function () {
    Route::post('deposit', [AccountController::class, 'deposit'])->name('deposit');
});
Route::apiResource('accounts', AccountController::class)->except('update');
Route::post('users/admin', [UserController::class, 'createAdmin'])->name('users.admin.store');
Route::apiResource('users', UserController::class);

