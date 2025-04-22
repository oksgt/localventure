<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController as AdminHomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes (Accessible Without Login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Authenticated Routes (Requires Login)
Route::middleware(['auth'])->group(function () {

    Route::get('/admin', [AdminHomeController::class, 'index'])->name('admin.home');

    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/data', [UserController::class, 'getUsers'])->name('admin.users.data');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.process');
});
