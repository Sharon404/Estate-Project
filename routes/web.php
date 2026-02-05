<?php

use App\Http\Controllers\Admin\BookingsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditLogsController;
use App\Http\Controllers\Admin\MpesaVerificationController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Payment\MpesaController;
use App\Http\Controllers\Payment\AdminPaymentController;
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
    
    // M-Pesa Manual Verification Routes
    Route::get('/mpesa-verification', [MpesaVerificationController::class, 'index'])->name('mpesa-verification.index');
    Route::get('/mpesa-verification/{submission}', [MpesaVerificationController::class, 'show'])->name('mpesa-verification.show');
    Route::put('/mpesa-verification/{submission}/verify', [MpesaVerificationController::class, 'verify'])->name('mpesa-verification.verify');
    Route::post('/mpesa-verification/{submission}/reject', [MpesaVerificationController::class, 'reject'])->name('mpesa-verification.reject');
    Route::post('/mpesa-verification/{submission}/check-status', [MpesaVerificationController::class, 'checkPaymentStatus'])->name('mpesa-verification.check-status');
    
    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UsersController::class, 'update'])->name('users.update');
    Route::get('/users/{user}/login-history', [\App\Http\Controllers\Admin\UsersController::class, 'loginHistory'])->name('users.login-history');
    
    // Properties Management
    Route::get('/properties', [\App\Http\Controllers\Admin\PropertyController::class, 'index'])->name('properties.index');
    Route::get('/properties/create', [\App\Http\Controllers\Admin\PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [\App\Http\Controllers\Admin\PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}', [\App\Http\Controllers\Admin\PropertyController::class, 'show'])->name('properties.show');
    Route::get('/properties/{property}/edit', [\App\Http\Controllers\Admin\PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [\App\Http\Controllers\Admin\PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [\App\Http\Controllers\Admin\PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::delete('/properties/{property}/photos/{image}', [\App\Http\Controllers\Admin\PropertyController::class, 'deletePhoto'])->name('properties.photos.delete');
    Route::post('/properties/{property}/photos/{image}/primary', [\App\Http\Controllers\Admin\PropertyController::class, 'setPrimaryPhoto'])->name('properties.photos.primary');
    
    // Refunds Management (Admin only)
    Route::get('/refunds', [\App\Http\Controllers\Admin\RefundsController::class, 'index'])->name('refunds.index');
    Route::get('/refunds/{refund}', [\App\Http\Controllers\Admin\RefundsController::class, 'show'])->name('refunds.show');
    Route::get('/refunds/create', [\App\Http\Controllers\Admin\RefundsController::class, 'create'])->name('refunds.create');
    Route::post('/refunds', [\App\Http\Controllers\Admin\RefundsController::class, 'store'])->name('refunds.store');
    Route::post('/refunds/{refund}/approve', [\App\Http\Controllers\Admin\RefundsController::class, 'approve'])->name('refunds.approve');
    Route::post('/refunds/{refund}/reject', [\App\Http\Controllers\Admin\RefundsController::class, 'reject'])->name('refunds.reject');
    Route::post('/refunds/{refund}/processed', [\App\Http\Controllers\Admin\RefundsController::class, 'markProcessed'])->name('refunds.processed');
    
    // Support Tickets
    Route::get('/tickets', [\App\Http\Controllers\Admin\TicketsController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\Admin\TicketsController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/assign', [\App\Http\Controllers\Admin\TicketsController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\Admin\TicketsController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/status', [\App\Http\Controllers\Admin\TicketsController::class, 'updateStatus'])->name('tickets.status');
    Route::post('/tickets/{ticket}/escalate', [\App\Http\Controllers\Admin\TicketsController::class, 'escalate'])->name('tickets.escalate');
    
    // Payouts Management
    Route::get('/payouts', [\App\Http\Controllers\Admin\PayoutsController::class, 'index'])->name('payouts.index');
    Route::get('/payouts/{payout}', [\App\Http\Controllers\Admin\PayoutsController::class, 'show'])->name('payouts.show');
    Route::get('/payouts/create', [\App\Http\Controllers\Admin\PayoutsController::class, 'create'])->name('payouts.create');
    Route::post('/payouts', [\App\Http\Controllers\Admin\PayoutsController::class, 'store'])->name('payouts.store');
    Route::post('/payouts/{payout}/approve', [\App\Http\Controllers\Admin\PayoutsController::class, 'approve'])->name('payouts.approve');
    Route::post('/payouts/{payout}/completed', [\App\Http\Controllers\Admin\PayoutsController::class, 'markCompleted'])->name('payouts.completed');
    Route::post('/payouts/{payout}/disputed', [\App\Http\Controllers\Admin\PayoutsController::class, 'markDisputed'])->name('payouts.disputed');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [\App\Http\Controllers\Admin\ReportsController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/occupancy', [\App\Http\Controllers\Admin\ReportsController::class, 'occupancy'])->name('reports.occupancy');
    Route::get('/reports/cancellations', [\App\Http\Controllers\Admin\ReportsController::class, 'cancellations'])->name('reports.cancellations');
    
    // Payment Reconciliation
    Route::get('/reconciliation', [\App\Http\Controllers\Admin\PaymentReconciliationController::class, 'index'])->name('reconciliation.index');
    Route::post('/reconciliation/{booking}/resolve', [\App\Http\Controllers\Admin\PaymentReconciliationController::class, 'resolveMismatch'])->name('reconciliation.resolve');
});

// Staff Dashboard
Route::middleware(['auth', 'role:staff', 'audit.request'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    
    // Staff Bookings (read-only)
    Route::get('/bookings', [\App\Http\Controllers\Staff\StaffBookingsController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\Staff\StaffBookingsController::class, 'show'])->name('bookings.show');
    
    // Staff Verification (can verify, cannot reject)
    Route::get('/verification', [\App\Http\Controllers\Staff\StaffVerificationController::class, 'index'])->name('verification.index');
    Route::get('/verification/{submission}', [\App\Http\Controllers\Staff\StaffVerificationController::class, 'show'])->name('verification.show');
    Route::put('/verification/{submission}/verify', [\App\Http\Controllers\Staff\StaffVerificationController::class, 'verify'])->name('verification.verify');
    
    // Staff Tickets (assigned or unassigned only)
    Route::get('/tickets', [\App\Http\Controllers\Staff\StaffTicketsController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [\App\Http\Controllers\Staff\StaffTicketsController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [\App\Http\Controllers\Staff\StaffTicketsController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/status', [\App\Http\Controllers\Staff\StaffTicketsController::class, 'updateStatus'])->name('tickets.status');
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

