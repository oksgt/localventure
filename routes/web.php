<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\BillingOperatorController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\GuestTypeController;
use App\Http\Controllers\HomeController as AdminHomeController;
use App\Http\Controllers\landingpage\BookingController;
use App\Http\Controllers\landingpage\HomeController;
use App\Http\Controllers\landingpage\PaymentConfirmationController;
use App\Http\Controllers\MappingUserController;
use App\Http\Controllers\MasterTicketController;
use App\Http\Controllers\PaymentOptionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMappingController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// Public Routes (Accessible Without Login)
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');

    Route::get('/', [HomeController::class, 'index'])->name('landing-page.home');
    Route::post('/search-tickets', [BookingController::class, 'searchTickets'])->name('search.tickets');
    Route::get('/tickets/results', [BookingController::class, 'showResults'])->name('tickets.results');
    Route::get('/get-provinces', [BookingController::class, 'getProvinces'])->name('get.provinces');
    Route::get('/get-regencies', [BookingController::class, 'getRegencies'])->name('get.regencies');
    Route::get('/get-districts', [BookingController::class, 'getDistricts'])->name('get.districts');
    Route::get('/get-pricing', [BookingController::class, 'getPricing'])->name('get.pricing');
    Route::get('/admin/payment-option/list', [PaymentOptionController::class, 'getActivePaymentTypes'])->name('admin.payment-option.list');
    Route::get('/bank-accounts', [BankAccountController::class, 'getActiveBankAccounts'])->name('bank.accounts.list');
    Route::post('/update-day-type', [BookingController::class, 'updateDayType'])->name('booking.updateDayType');
    // Route::post('/finish-payment', [BookingController::class, 'finishPayment'])->name('booking.finishPayment');

    Route::get('/test-email', [HomeController::class, 'testEmail'])->name('test-email');

    Route::post('/finish-payment', [BookingController::class, 'finishPayment'])
    //->withoutMiddleware([VerifyCsrfToken::class]) // ✅ Disables CSRF for this route
    ->name('booking.finishPayment');

    // Route::get('/get-finish-payment', [BookingController::class, 'getFinishPayment']);

    Route::get('/ticket/pay', [BookingController::class, 'finishPaymentPage'])->name('booking.finish-payment-page');

    Route::get('/download-invoice/{id}', [BookingController::class, 'downloadInvoice'])->name('invoice');

    Route::get('/result-finish-payment/{id}', [BookingController::class, 'showFinishPayment'])->name('finish.payment.view');

    Route::get('/cek/{billing?}', [BookingController::class, 'cek'])->name('cek');
    Route::post('/payment-confirmation/store', [PaymentConfirmationController::class, 'store'])->name('payment.store');

    // Route::get('/payment-confirmation-auto', [PaymentConfirmationController::class, 'autoConfirmPayments']);

    Route::get('/download/ticket/baru/{id}', [BookingController::class, 'downloadTicketBaru']);
    Route::post('/ticket/check-in', [BookingController::class, 'updateCheckIn'])->name('ticket.updateCheckIn');

    Route::middleware(['auth'])->group(function () {

        // 🔹 Admin & Super Admin (role_id = 1,2) - Master Ticket
        Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
            Route::get('/admin/online-transaction', [TransactionController::class, 'index'])->name('admin.transaction.index');
            Route::get('/admin/online-transaction/data', [TransactionController::class, 'getData'])->name('admin.transaction.data');
            Route::get('/admin/online-transaction/detail/{id}', [TransactionController::class, 'detail'])->name('admin.transaction.detail');
            Route::post('/admin/payment-confirmation', [PaymentConfirmationController::class, 'show'])->name('admin.payment.show');
            Route::post('/admin/payment-update', [PaymentConfirmationController::class, 'updatePayment'])->name('admin.payment.update');

            Route::get('/admin/onsite-transaction', [TransactionController::class, 'transactionOnsite'])->name('admin.transaction-onsite.index');
            Route::get('/admin/onsite-transaction/data', [TransactionController::class, 'getDataOnsite'])->name('admin.transaction-onsite.data');
            Route::get('/admin/onsite-transaction/detail/{id}', [TransactionController::class, 'detailOnSite'])->name('admin.transaction-onsite.detail');
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

        // 🔹 Super Admin Only (role_id = 1)
        Route::middleware([RoleMiddleware::class . ':1'])->group(function () {
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
            Route::get('/roles', [UserController::class, 'getRoles'])->name('roles.list');
            Route::get('/admins', [UserController::class, 'getAdmins'])->name('admins.list');
            Route::post('/admin/users/store', [UserController::class, 'store'])->name('users.store');
            Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/admin/users/{id}/update', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{id}/delete', [UserController::class, 'softDelete'])->name('users.delete');
        });

        // 🔹 Super Admin (role_id = 1) - Mapping Users
        Route::middleware([RoleMiddleware::class . ':1'])->group(function () {
            Route::get('/admin/mapping-user', [UserMappingController::class, 'index'])->name('admin.mapping-user.index');
            Route::get('/admin/mapping-user/data', [UserMappingController::class, 'getData'])->name('admin.mapping-user.data');
            Route::post('/admin/mapping-users/store', [UserMappingController::class, 'store'])->name('admin.mapping-users.store');
            Route::put('/admin/mapping-users/{id}', [UserMappingController::class, 'update'])->name('admin.mapping-users.update');
            Route::delete('/admin/mapping-users/{id}', [UserMappingController::class, 'destroy'])->name('admin.mapping-users.destroy');
        });

        // 🔹 Super Admin & Admin (role_id = 1,2) - Destinations
        Route::middleware([RoleMiddleware::class . ':1'])->group(function () {
            Route::get('/admin/destinations', [DestinationController::class, 'index'])->name('admin.destinations.index');
            Route::get('/admin/destinations/data', [DestinationController::class, 'getDestinations'])->name('admin.destinations.data');
            Route::post('/admin/destinations/store', [DestinationController::class, 'store'])->name('admin.destinations.store');
            Route::get('/admin/destinations/{id}/edit', [DestinationController::class, 'edit'])->name('admin.destinations.edit');
            Route::put('/admin/destinations/{id}/update', [DestinationController::class, 'update'])->name('admin.destinations.update');
            Route::delete('/admin/destinations/{id}', [DestinationController::class, 'destroy'])->name('admin.destinations.destroy');
        });

        // 🔹 Admin & Super Admin (role_id = 1,2) - Destination Gallery
        Route::middleware([RoleMiddleware::class . ':1'])->group(function () {
            Route::get('/admin/destination-gallery/{destinationId}', [DestinationController::class, 'fetchGallery'])->name('admin.destination-gallery.fetch');
            Route::post('/admin/destination-gallery/upload', [DestinationController::class, 'upload'])->name('admin.destination-gallery.upload');
            Route::delete('/admin/destination-gallery/{id}/remove', [DestinationController::class, 'remove'])->name('admin.destination-gallery.remove');
        });

        // 🔹 General Lists (All Authenticated Users)
        Route::middleware([RoleMiddleware::class . ':1,2,3'])->group(function () {
            Route::get('/users/list', [UserController::class, 'list'])->name('users.list');
            Route::get('/destinations/list', [DestinationController::class, 'list'])->name('destinations.list');
            Route::get('/guest-types/list', [GuestTypeController::class, 'list'])->name('guest-types.list');
        });

        // 🔹 Super Admin, Admin, Operator (role_id = 1,2,3)
        Route::middleware([RoleMiddleware::class . ':1,2,3'])->group(function () {
            Route::get('/profile', [UserController::class, 'getProfile'])->name('profile.get');
            Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
            Route::put('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.updatePassword');
        });

        Route::middleware([RoleMiddleware::class . ':1,2,3'])->group(function () {
            Route::get('/download/ticket/{billing}', [TransactionController::class, 'downloadTicket'])->name('download.ticket');
            Route::get('/admin', [AdminHomeController::class, 'index'])->name('admin.home');
            Route::get('/get-visitor-chart-data', [AdminHomeController::class, 'getVisitorDataChart'])->name('admin.visitor.chart.data');
        });

        Route::middleware([RoleMiddleware::class . ':1,2'])->group(function () {
            Route::get('/admin/billing-operator', [BillingOperatorController::class, 'index'])->name('admin.billing.operator');
            Route::get('/operator/billing-history', [BillingOperatorController::class, 'billingHistory'])->name('operator.billingHistory');
            Route::get('/operator/billing-operator-detail', [BillingOperatorController::class, 'billingOperatorDetail'])->name('operator.billingOperatorDetail');
            Route::post('/operator/approve-payment', [BillingOperatorController::class, 'approvePayment'])->name('operator.approvePayment');
        });


        Route::middleware([RoleMiddleware::class . ':1'])->group(function () {
            Route::prefix('admin/payment-option')->group(function () {
                Route::get('/', [PaymentOptionController::class, 'index'])->name('admin.payment-option.index');
                Route::get('/data', [PaymentOptionController::class, 'getData'])->name('admin.payment-option.data');
                Route::post('/store', [PaymentOptionController::class, 'store'])->name('admin.payment-option.store');
                Route::get('/edit/{id}', [PaymentOptionController::class, 'edit'])->name('admin.payment-option.edit');
                Route::post('/update/{id}', [PaymentOptionController::class, 'update'])->name('admin.payment-option.update');
                Route::delete('/delete/{id}', [PaymentOptionController::class, 'destroy'])->name('admin.payment-option.delete');
            });

            // Route::get('/admin/master-ticket/data', [MasterTicketController::class, 'getData'])->name('admin.master-ticket.data');
            // Route::post('/admin/master-ticket/store', [MasterTicketController::class, 'store'])->name('admin.master-ticket.store');
            // Route::put('/admin/master-ticket/{id}', [MasterTicketController::class, 'update'])->name('admin.master-ticket.update');
            // Route::delete('/admin/master-ticket/{id}', [MasterTicketController::class, 'destroy'])->name('admin.master-ticket.destroy');
            // Route::get('/admin/master-ticket/edit/{id}', [MasterTicketController::class, 'edit'])->name('admin.master-ticket.edit');
        });

        Route::middleware([RoleMiddleware::class . ':3'])->group(function () {
            Route::get('/ticket-purchase', [AdminHomeController::class, 'ticketPurchase'])->name('ticket-purchase.index');
            Route::get('/form-ticket-purchase/{destinationId}', [AdminHomeController::class, 'formTicketPurchase'])->name('form-ticket-purchase.index');
            Route::post('/ticket-purchase', [BookingController::class, 'storeTicketPurchase'])->name('ticket.purchase');
            Route::get('/admin/online-transaction/scan/{billing}', [TransactionController::class, 'detailOperator']);

            Route::get('/history', [TransactionController::class, 'history'])->name('history');
            Route::delete('/admin/online-transaction/delete/{id}', [TransactionController::class, 'delete'])->name('transaction.delete');
            Route::get('/operator-billing', [TransactionController::class, 'OperatorBilling'])->name('operator-billing');
            Route::post('/operator/create-billing', [TransactionController::class, 'createBilling'])->name('operator.createBilling');
            Route::delete('/operator/delete-transaction', [TransactionController::class, 'deleteOperatorBilling'])->name('operator.deleteTransaction');
            Route::get('/operator/history-detail', [TransactionController::class, 'historyDetail'])->name('operator.historyDetail');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout.process');
    });
