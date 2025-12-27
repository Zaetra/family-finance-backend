<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FamilyGroupController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('family-groups', FamilyGroupController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('accounts', AccountController::class);

    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
});
