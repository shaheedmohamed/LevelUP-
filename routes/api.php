<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LogController as AdminLogController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Admin routes
Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function(){
    Route::get('/stats', [DashboardController::class, 'stats']);

    Route::get('/users', [AdminUserController::class, 'index']);
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole']);
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy']);

    Route::get('/logs', [AdminLogController::class, 'index']);

    Route::get('/settings', [AdminSettingController::class, 'index']);
    Route::post('/settings', [AdminSettingController::class, 'update']);
});
