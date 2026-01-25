<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\MpesaController;
use App\Http\Controllers\Payment\MpesaC2BController;
use App\Http\Controllers\Booking\BookingStatusController;

// Public M-PESA callback (no CSRF)
Route::post('mpesa/callback', [MpesaController::class, 'callback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('api.mpesa.callback');

// M-PESA C2B Validation and Confirmation (no CSRF)
Route::post('payment/c2b/validate', [MpesaC2BController::class, 'validateCallback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('api.c2b.validate');

Route::post('payment/c2b/confirm', [MpesaC2BController::class, 'confirmCallback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('api.c2b.confirm');

// Test endpoint to verify C2B URLs are accessible
Route::get('payment/c2b/test', function() {
    return response()->json([
        'status' => 'OK',
        'message' => 'C2B endpoints are accessible',
        'validation_url' => url('/api/payment/c2b/validate'),
        'confirmation_url' => url('/api/payment/c2b/confirm'),
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('api.c2b.test');

// Booking status polling endpoint
Route::get('booking/{reference}/status', [BookingStatusController::class, 'show'])
    ->name('api.booking.status');
