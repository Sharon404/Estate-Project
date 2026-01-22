<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Payment\MpesaController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Routes - Authenticated Users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Booking Routes - Public (can be called before guest logs in)
Route::apiResource('bookings', BookingController::class, ['only' => ['store']]);
Route::get('/bookings/{booking}/summary', [BookingController::class, 'summary']);
Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm']);

// Payment Routes - M-PESA Integration
Route::prefix('payment/mpesa')->name('mpesa.')->group(function () {
    Route::post('stk', [MpesaController::class, 'initiateStk'])->name('stk');
    Route::get('stk/{stkRequest}/status', [MpesaController::class, 'stkStatus'])->name('stk-status');
    Route::post('callback', [MpesaController::class, 'callback'])->name('callback');
});
