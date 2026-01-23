<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Payment\MpesaController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\AdminPaymentController;
use App\Http\Controllers\FrontendController;

// Frontend Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/home-2', [FrontendController::class, 'home2'])->name('frontend.home-2');
Route::get('/home-3', [FrontendController::class, 'home2'])->name('frontend.home-3');
Route::get('/home-4', [FrontendController::class, 'home2'])->name('frontend.home-4');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/properties', [FrontendController::class, 'properties'])->name('properties');
Route::get('/property/{id}', [FrontendController::class, 'propertySingle'])->name('property.single');
Route::get('/facilities', [FrontendController::class, 'facilities'])->name('facilities');
Route::get('/offers', [FrontendController::class, 'offers'])->name('offers');
Route::get('/offer/{id}', [FrontendController::class, 'offerSingle'])->name('offer.single');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');
Route::get('/gallery/carousel', [FrontendController::class, 'gallery'])->name('gallery.carousel');
Route::get('/testimonials', [FrontendController::class, 'testimonials'])->name('testimonials');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog/{id}', [FrontendController::class, 'blogSingle'])->name('blog.single');
Route::get('/reservation', [FrontendController::class, 'reservation'])->name('reservation');

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
Route::prefix('payment')->name('payment.')->group(function () {
    // Payment intent creation (before STK)
    Route::post('intents', [PaymentController::class, 'createIntent'])->name('intent-create');
    Route::get('intents/{paymentIntent}', [PaymentController::class, 'getIntent'])->name('intent-get');
    Route::get('bookings/{booking}/options', [PaymentController::class, 'getPaymentOptions'])->name('options');
    Route::get('bookings/{booking}/history', [PaymentController::class, 'getPaymentHistory'])->name('history');

    // Manual M-PESA entry (when STK fails/times out)
    Route::post('manual-entry', [PaymentController::class, 'submitManualPayment'])->name('manual-entry-submit');

    // M-PESA STK Push
    Route::prefix('mpesa')->name('mpesa.')->group(function () {
        Route::post('stk', [MpesaController::class, 'initiateStk'])->name('stk');
        Route::get('stk/{stkRequest}/status', [MpesaController::class, 'stkStatus'])->name('stk-status');
        Route::post('callback', [MpesaController::class, 'callback'])->name('callback');
    });
});

// Admin Payment Routes - Manual verification
Route::middleware('auth')->prefix('admin/payment')->name('admin.payment.')->group(function () {
    // Manual submission management
    Route::get('manual-submissions/pending', [AdminPaymentController::class, 'getPendingSubmissions'])->name('manual-pending');
    Route::get('manual-submissions/{submission}', [AdminPaymentController::class, 'getSubmissionDetails'])->name('manual-details');
    Route::post('manual-submissions/{submission}/verify', [AdminPaymentController::class, 'verifySubmission'])->name('manual-verify');
    Route::post('manual-submissions/{submission}/reject', [AdminPaymentController::class, 'rejectSubmission'])->name('manual-reject');

    // Statistics
    Route::get('statistics', [AdminPaymentController::class, 'getStatistics'])->name('statistics');
});
