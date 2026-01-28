<?php

use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\MpesaController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuditController;

// Frontend Routes
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'contactStore'])->name('contact.store');
Route::get('/properties', [FrontendController::class, 'properties'])->name('properties');
Route::get('/property/{id}', [FrontendController::class, 'propertySingle'])->name('property.single');
Route::get('/facilities', [FrontendController::class, 'facilities'])->name('facilities');
Route::get('/gallery', [FrontendController::class, 'gallery'])->name('gallery');
Route::get('/testimonials', [FrontendController::class, 'testimonials'])->name('testimonials');
Route::get('/blog', [FrontendController::class, 'blog'])->name('blog');
Route::get('/blog/{id}', [FrontendController::class, 'blogSingle'])->name('blog.single');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Routes - Authenticated Users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $role = strtolower($user->role ?? '');

        if ($role === 'admin' || $role === 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('staff.dashboard');
    })->name('dashboard');
});

// Admin Dashboard
Route::middleware(['auth', 'role:admin', 'audit.request'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}', [BookingsController::class, 'show'])->name('booking-detail');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/audit-logs', [AuditLogsController::class, 'index'])->name('audit-logs');
    Route::get('/audit-logs/{auditLog}', [AuditLogsController::class, 'show'])->name('audit-log-detail');
});

// Staff Dashboard
Route::middleware(['auth', 'role:staff', 'audit.request'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
});

// Booking Routes - Public Three-Step Flow: Form → Confirm → Store
// Step 1: GET /reservation - Display reservation form (no submission)
Route::get('/reservation', [BookingController::class, 'reservationForm'])->name('reservation');

// Step 2: GET /reservation/confirm - Display confirmation before POST
Route::get('/reservation/confirm', [BookingController::class, 'confirmForm'])->name('reservation.confirm');

// Step 3: POST /booking/store - Create booking after @csrf validation
Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');

// Booking summary/history (after booking created)
Route::get('/bookings/{booking}/summary', [BookingController::class, 'showSummary'])->name('booking.summary');

// CSRF validation test routes
Route::get('/csrf-test', function () {
    return view('booking.csrf-test');
})->name('csrf.test');

Route::post('/csrf-test', function () {
    return back()->with('success', 'CSRF validation passed successfully.');
})->name('csrf.test.submit');

// Payment Routes - M-PESA Integration
Route::prefix('payment')->name('payment.')->group(function () {
    // Payment page display
    Route::get('booking/{booking}', [PaymentController::class, 'showPaymentPage'])->name('show');
    
    // Payment intent creation (before STK)
    Route::post('intents', [PaymentController::class, 'createIntent'])->name('intent-create');
    Route::get('intents/{paymentIntent}', [PaymentController::class, 'getIntent'])->name('intent-get');
    Route::get('bookings/{booking}/options', [PaymentController::class, 'getPaymentOptions'])->name('options');
    Route::get('bookings/{booking}/history', [PaymentController::class, 'getPaymentHistory'])->name('history');

    // Manual M-PESA entry (when STK fails/times out)
    Route::post('manual-entry', [PaymentController::class, 'submitManualPayment'])->name('manual-entry-submit');

    // Receipt retrieval
    Route::get('receipts/{receiptNo}', [PaymentController::class, 'getReceiptByNumber'])->name('receipt-get');
    Route::get('bookings/{bookingId}/receipts', [PaymentController::class, 'getBookingReceipts'])->name('receipt-list');
    Route::get('bookings/{bookingId}/receipts/{receiptNo}', [PaymentController::class, 'getBookingReceipt'])->name('receipt-get-booking');
    
    // PDF Receipt Download
    Route::get('booking/{booking}/receipt/download', [PaymentController::class, 'downloadReceipt'])->name('receipt-download');

    // M-PESA STK Push
    Route::prefix('mpesa')->name('mpesa.')->group(function () {
        Route::post('stk', [MpesaController::class, 'initiateStk'])->name('stk');
        Route::get('stk/{stkRequest}/status', [MpesaController::class, 'stkStatus'])->name('stk-status');
        Route::post('callback', [MpesaController::class, 'callback'])->name('callback');
    });
});

// Public callback endpoint to match external URL (e.g., https://<ngrok>/api/mpesa/callback)
Route::post('api/mpesa/callback', [MpesaController::class, 'callback'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('payment.mpesa.callback-external');

// Admin Payment Routes - Manual verification
Route::middleware(['auth', 'audit.request'])->prefix('admin/payment')->name('admin.payment.')->group(function () {
    // Dashboard
    Route::get('verification-dashboard', [AdminPaymentController::class, 'verificationDashboard'])->name('verification-dashboard');
    
    // Manual submission management
    Route::get('manual-submissions/pending', [AdminPaymentController::class, 'getPendingSubmissions'])->name('manual-pending');
    Route::get('manual-submissions/{submission}', [AdminPaymentController::class, 'getSubmissionDetails'])->name('manual-details');
    Route::post('manual-submissions/{submission}/verify', [AdminPaymentController::class, 'verifySubmission'])->name('manual-verify');
    Route::post('manual-submissions/{submission}/reject', [AdminPaymentController::class, 'rejectSubmission'])->name('manual-reject');

    // Email management
    Route::post('emails/{emailOutbox}/resend', [AdminPaymentController::class, 'resendReceiptEmail'])->name('email-resend');
    Route::get('receipts/{receipt}/email-history', [AdminPaymentController::class, 'getReceiptEmailHistory'])->name('email-history');
    Route::get('emails/statistics', [AdminPaymentController::class, 'getEmailStatistics'])->name('email-statistics');

    // Statistics
    Route::get('statistics', [AdminPaymentController::class, 'getStatistics'])->name('statistics');
});

// Admin Audit Routes - Logging and compliance
Route::middleware(['auth', 'audit.request'])->prefix('admin/audit')->name('admin.audit.')->group(function () {
    Route::get('logs', [AuditController::class, 'index'])->name('logs');
    Route::get('logs/{id}', [AuditController::class, 'show'])->name('logs-show');
    Route::get('resource', [AuditController::class, 'forResource'])->name('resource');
    Route::get('users/{userId}', [AuditController::class, 'forUser'])->name('user');
    Route::get('actions', [AuditController::class, 'byAction'])->name('action');
    Route::get('ip', [AuditController::class, 'byIp'])->name('ip');
    Route::get('suspicious', [AuditController::class, 'suspiciousActivity'])->name('suspicious');
    Route::get('statistics', [AuditController::class, 'statistics'])->name('statistics');
    Route::get('export', [AuditController::class, 'export'])->name('export');
});
