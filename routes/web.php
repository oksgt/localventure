<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\GuestTypeController;
use App\Http\Controllers\HomeController as AdminHomeController;
use App\Http\Controllers\landingpage\HomeController;
use App\Http\Controllers\MappingUserController;
use App\Http\Controllers\MasterTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMappingController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;


// Public Routes (Accessible Without Login)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    Route::get('/', [HomeController::class, 'index'])->name('landing-page.home');

    Route::middleware(['auth'])->group(function () {

        // 🔹 Super Admin Only (role_id = 1)
        Route::middleware([RoleMiddleware::class . ':1'])->group(function () {
            Route::get('/admin', [AdminHomeController::class, 'index'])->name('admin.home');

            Route::prefix('admin/bank-accounts')->group(function () {
                Route::get('/', [BankAccountController::class, 'index'])->name('admin.bank-accounts.index');
                Route::get('/data', [BankAccountController::class, 'getData'])->name('admin.bank-accounts.data');
                Route::post('/store', [BankAccountController::class, 'store'])->name('admin.bank-accounts.store');
                Route::put('/update/{id}', [BankAccountController::class, 'update'])->name('admin.bank-accounts.update');
                Route::delete('/delete/{id}', [BankAccountController::class, 'destroy'])->name('admin.bank-accounts.destroy');
                Route::get('/edit/{id}', [BankAccountController::class, 'edit'])->name('admin.bank-accounts.edit');
            });
        });

        // 🔹 Admin & Super Admin (role_id = 1,2)
        Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
            Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/admin/users/data', [UserController::class, 'getUsers'])->name('admin.users.data');
            Route::post('/admin/users/store', [UserController::class, 'store'])->name('users.store');
            Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/admin/users/{id}/update', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{id}/delete', [UserController::class, 'softDelete'])->name('users.delete');
        });

        // 🔹 Super Admin, Admin, Operator (role_id = 1,2,3)
        Route::middleware([RoleMiddleware::class . ':1,2,3'])->group(function () {
            Route::get('/profile', [UserController::class, 'getProfile'])->name('profile.get');
            Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
            Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');
        });

        // 🔹 Super Admin & Admin (role_id = 1,2) - Destinations
        Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
            Route::get('/admin/destinations', [DestinationController::class, 'index'])->name('admin.destinations.index');
            Route::get('/admin/destinations/data', [DestinationController::class, 'getDestinations'])->name('admin.destinations.data');
            Route::post('/admin/destinations/store', [DestinationController::class, 'store'])->name('admin.destinations.store');
            Route::get('/admin/destinations/{id}/edit', [DestinationController::class, 'edit'])->name('admin.destinations.edit');
            Route::put('/admin/destinations/{id}/update', [DestinationController::class, 'update'])->name('admin.destinations.update');
            Route::delete('/admin/destinations/{id}', [DestinationController::class, 'destroy'])->name('admin.destinations.destroy');
        });

        // 🔹 Admin & Super Admin (role_id = 1,2) - Destination Gallery
        Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
            Route::get('/admin/destination-gallery/{destinationId}', [DestinationController::class, 'fetchGallery'])->name('admin.destination-gallery.fetch');
            Route::post('/admin/destination-gallery/upload', [DestinationController::class, 'upload'])->name('admin.destination-gallery.upload');
            Route::delete('/admin/destination-gallery/{id}/remove', [DestinationController::class, 'remove'])->name('admin.destination-gallery.remove');
        });

        // 🔹 Super Admin (role_id = 1) - Mapping Users
        Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
            Route::get('/admin/mapping-user', [UserMappingController::class, 'index'])->name('admin.mapping-user.index');
            Route::get('/admin/mapping-user/data', [UserMappingController::class, 'getData'])->name('admin.mapping-user.data');
            Route::post('/admin/mapping-users/store', [UserMappingController::class, 'store'])->name('admin.mapping-users.store');
            Route::put('/admin/mapping-users/{id}', [UserMappingController::class, 'update'])->name('admin.mapping-users.update');
            Route::delete('/admin/mapping-users/{id}', [UserMappingController::class, 'destroy'])->name('admin.mapping-users.destroy');
        });

        // 🔹 Admin & Super Admin (role_id = 1,2) - Master Ticket
        Route::middleware([RoleMiddleware::class . ':1'])->group(function () {
            Route::get('/admin/master-ticket', [MasterTicketController::class, 'index'])->name('admin.master-ticket.index');
            Route::get('/admin/master-ticket/data', [MasterTicketController::class, 'getData'])->name('admin.master-ticket.data');
            Route::post('/admin/master-ticket/store', [MasterTicketController::class, 'store'])->name('admin.master-ticket.store');
            Route::put('/admin/master-ticket/{id}', [MasterTicketController::class, 'update'])->name('admin.master-ticket.update');
            Route::delete('/admin/master-ticket/{id}', [MasterTicketController::class, 'destroy'])->name('admin.master-ticket.destroy');
            Route::get('/admin/master-ticket/edit/{id}', [MasterTicketController::class, 'edit'])->name('admin.master-ticket.edit');
        });

        // 🔹 General Lists (All Authenticated Users)
        Route::middleware([RoleMiddleware::class . ':1,2,3'])->group(function () {
            Route::get('/users/list', [UserController::class, 'list'])->name('users.list');
            Route::get('/destinations/list', [DestinationController::class, 'list'])->name('destinations.list');
            Route::get('/guest-types/list', [GuestTypeController::class, 'list'])->name('guest-types.list');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout.process');
    });
