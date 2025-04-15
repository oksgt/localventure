<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\landingpage\HomeController;

//give the route '/' and direct it to the index method of the HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');
