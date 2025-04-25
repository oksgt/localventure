<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinationController;
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
    Route::get('/admin/users/add', [UserController::class, 'addUser'])->name('admin.users.add');
    Route::get('/roles', [UserController::class, 'getRoles'])->name('roles.list');
    Route::get('/admins', [UserController::class, 'getAdmins'])->name('admins.list');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/admin/users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}/delete', [UserController::class, 'softDelete'])->name('users.delete');

    Route::get('/profile', [UserController::class, 'getProfile'])->name('profile.get');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');


    Route::get('/admin/destinations', [DestinationController::class, 'index'])->name('admin.destinations.index');
    Route::get('/admin/destinations/data', [DestinationController::class, 'getDestinations'])->name('admin.destinations.data');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.process');
});
