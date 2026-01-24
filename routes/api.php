<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payment\MpesaController;

// Public M-PESA callback (no CSRF)
Route::post('mpesa/callback', [MpesaController::class, 'callback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('api.mpesa.callback');
