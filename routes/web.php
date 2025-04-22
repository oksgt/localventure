<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController as AdminHomeController;
use Illuminate\Support\Facades\Route;

// Public Routes (Accessible Without Login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Authenticated Routes (Requires Login)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.process');
    Route::get('/admin', [AdminHomeController::class, 'index'])->name('admin.home');
});
