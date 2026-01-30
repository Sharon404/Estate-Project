@extends('frontend.layouts.app')

@section('title', 'Payment - ' . $booking->booking_ref)

@section('content')
<!-- Payment Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Header -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h1 class="h3 mb-2">Complete Your Payment</h1>
                        <p class="text-muted">Booking Reference: <strong class="text-primary">{{ $booking->booking_ref }}</strong></p>
                    </div>
                </div>

                <!-- Booking Summary Card -->
                <div class="card shadow-sm overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-primary text-white py-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <small class="text-white-50">Property</small>
                                <p class="mb-0 fw-bold">{{ $booking->property->name }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-white-50">Check-in</small>
                                <p class="mb-0 fw-bold">{{ $booking->check_in->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-white-50">Check-out</small>
                                <p class="mb-0 fw-bold">{{ $booking->check_out->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-white-50">Duration</small>
                                <p class="mb-0 fw-bold">{{ $booking->nights }} nights</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Amount -->
                    <div class="card-body bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Total Amount Due:</span>
                            <h4 class="mb-0 text-primary">{{ number_format($booking->total_amount, 2) }} {{ $booking->currency }}</h4>
                        </div>
                        @if($booking->status === 'PARTIALLY_PAID')
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Already Paid:</span>
                                <span class="fw-bold text-success">{{ number_format($booking->amount_paid, 2) }} {{ $booking->currency }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Remaining Due:</span>
                                <span class="fw-bold text-danger">{{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Form -->
                    <div class="card-body" id="payment-container">
                        <div class="alert alert-info mb-4">
                            <h5 class="mb-2">
                                <i class="fas fa-info-circle me-2"></i>
                                How Payment Works
                            </h5>
                            <ol class="mb-0">
                                <li>Pay via M-PESA using the Paybill details below</li>
                                <li>Enter your M-PESA receipt code on this page</li>
                                <li>Our admin will verify your payment and confirm your booking</li>
                                <li>You'll receive confirmation via email once verified</li>
                            </ol>
                        </div>

                        <!-- Paybill Information -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">M-PESA Payment Details</h6>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <small class="text-muted">Paybill Number:</small>
                                        <div class="d-flex gap-2 align-items-center">
                                            <code class="bg-white px-2 py-1 rounded fw-bold text-primary">{{ config('mpesa.business_shortcode', '174379') }}</code>
                                            <button onclick="copyToClipboard('{{ config('mpesa.business_shortcode', '174379') }}')" class="btn btn-sm btn-link" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted">Amount:</small>
                                        <p class="mb-0 fw-bold">{{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <small class="text-muted">Account Number:</small>
                                        <div class="d-flex gap-2 align-items-center">
                                            <code class="bg-white px-2 py-1 rounded fw-bold text-primary">{{ $booking->booking_ref }}</code>
                                            <button onclick="copyToClipboard('{{ $booking->booking_ref }}')" class="btn btn-sm btn-link" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Enter this as the Account Number</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">How to Pay via M-PESA:</h6>
                                <ol class="mb-0">
                                    <li>Open M-PESA on your phone</li>
                                    <li>Select <strong>"Lipa na M-PESA"</strong> then <strong>"Pay Bill"</strong></li>
                                    <li>Enter Business Number: <code>{{ config('mpesa.business_shortcode', '174379') }}</code></li>
                                    <li>Enter Account Number: <code>{{ $booking->booking_ref }}</code></li>
                                    <li>Enter Amount: <strong>{{ number_format($booking->amount_due, 2) }}</strong></li>
                                    <li>Enter your M-PESA PIN and confirm</li>
                                    <li>You'll receive an SMS with an M-PESA receipt code</li>
                                </ol>
                            </div>
                        </div>

                        <!-- M-PESA Code Entry -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">Submit Your M-PESA Receipt Code</h6>
                                <p class="text-muted mb-3">After completing your payment, enter the M-PESA receipt code from your confirmation SMS:</p>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">M-PESA Receipt Code *</label>
                                    <input 
                                        type="text" 
                                        id="mpesa_receipt_code" 
                                        class="form-control form-control-lg"
                                        placeholder="e.g. SH12ABC34D"
                                        maxlength="20"
                                        required
                                    />
                                    <small class="text-muted">This is the code from your M-PESA confirmation SMS</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Your Phone Number *</label>
                                    <input 
                                        type="tel" 
                                        id="phone_input" 
                                        class="form-control form-control-lg"
                                        placeholder="0712345678 or +254712345678"
                                        required
                                    />
                                    <small class="text-muted">The phone number you used for M-PESA payment</small>
                                </div>

                                <button 
                                    id="submit_code_btn" 
                                    onclick="submitMpesaCode()"
                                    class="btn btn-primary btn-lg w-100 fw-bold"
                                >
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Submit for Verification
                                </button>
                            </div>
                        </div>

                        <!-- Submission Result -->
                        <div id="submission_success" class="alert alert-success d-none">
                            <h5 class="mb-2"><i class="fas fa-check-circle me-2"></i>Code Submitted Successfully!</h5>
                            <p class="mb-0">Your M-PESA code has been received. An administrator will verify your payment shortly and contact you via email or phone to confirm your booking.</p>
                        </div>

                        <div id="submission_error" class="alert alert-danger d-none">
                            <h5 class="mb-2"><i class="fas fa-exclamation-circle me-2"></i>Submission Failed</h5>
                            <p id="submission_error_text" class="mb-0"></p>
                            <button onclick="clearSubmissionError()" class="btn btn-sm btn-outline-danger mt-2">Try Again</button>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Need Help?</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h6 class="mb-1">Contact Support</h6>
                                <p class="text-muted mb-0">
                                    Email: bookings@tausivacations.com<br/>
                                    Phone: +254 718 756 254
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">Payment Information</h6>
                                <p class="text-muted mb-0">
                                    M-PESA Payment Gateway<br/>
                                    Secure & Encrypted Transactions
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript -->
<script>
    const bookingId = {{ $booking->id }};
    const bookingRef = '{{ $booking->booking_ref }}';
    const amount = {{ $booking->amount_due }};
    const currency = '{{ $booking->currency }}';
    const till = '{{ config("mpesa.till_number", "*138#") }}';
    const bookingStatus = '{{ $booking->status }}';
    
    let paymentIntentId = null;
    let stkPollingInterval = null;
    let statusPollingInterval = null;

    // Check if booking is already paid on page load
    window.addEventListener('DOMContentLoaded', function() {
        if (bookingStatus === 'PAID' || bookingStatus === 'COMPLETED') {
            showSuccess('Payment completed! You can download your receipt below.');
        }
    });

    // Handle Payment Method
    async function handlePaymentMethod() {
        const method = document.querySelector('input[name="payment_method"]:checked')?.value || 'stk';

        if (method === 'paybill') {
            showPendingState();
            startStatusPolling();
            return;
        }

        const phone = document.getElementById('phone_input').value.trim();
        if (!phone) {
            showError('Please enter your phone number');
            return;
        }

        try {
            console.log('Starting STK payment flow for booking:', bookingId, 'amount:', amount);
            
            // Show loading state
            showLoading('Sending M-PESA prompt to your phone...');

            // Create payment intent
            console.log('Creating payment intent...');
            const intentResponse = await fetch('/payment/intents', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    booking_id: bookingId,
                    amount: amount
                })
            });

            console.log('Payment intent response status:', intentResponse.status);
            const intentData = await intentResponse.json();
            console.log('Payment intent data:', intentData);
            
            if (!intentData.success) {
                throw new Error(intentData.message || 'Failed to create payment intent');
            }

            paymentIntentId = intentData.data.payment_intent_id;

            // Normalize phone to E.164 for M-PESA
            const phoneE164 = phone.startsWith('+') ? phone : `+254${phone.replace(/^0/, '')}`;
            console.log('Normalized phone:', phoneE164);

            // Initiate STK
            console.log('Initiating STK push...');
            const stkResponse = await fetch('/payment/mpesa/stk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntentId,
                    phone_e164: phoneE164
                })
            });

            console.log('STK response status:', stkResponse.status);
            const stkData = await stkResponse.json();
            console.log('STK data:', stkData);

            if (!stkData.success) {
                // STK failed - show manual fallback after timeout
                updateLoadingMessage('STK prompt timed out. Showing manual payment option...');
                setTimeout(showManualFallback, 2000);
                return;
            }

@section('scripts')
<script>
    const bookingId = {{ $booking->id }};
    const bookingRef = '{{ $booking->booking_ref }}';
    const amount = {{ $booking->amount_due }};
    const currency = '{{ $booking->currency }}';

    // Submit M-PESA Code Function
    async function submitMpesaCode() {
        const mpesaCode = document.getElementById('mpesa_receipt_code').value.trim().toUpperCase();
        const phone = document.getElementById('phone_input').value.trim();

        // Validation
        if (!mpesaCode) {
            showSubmissionError('Please enter your M-PESA receipt code');
            return;
        }

        if (!phone) {
            showSubmissionError('Please enter your phone number');
            return;
        }

        // Disable button
        const submitBtn = document.getElementById('submit_code_btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

        try {
            // First create payment intent
            const intentResponse = await fetch('/payment/intents', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    booking_id: bookingId,
                    amount: amount
                })
            });

            const intentData = await intentResponse.json();

            if (!intentData.success) {
                throw new Error(intentData.message || 'Failed to create payment intent');
            }

            const paymentIntentId = intentData.data.payment_intent_id;

            // Submit manual M-PESA code
            const response = await fetch('/payment/manual-entry', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntentId,
                    mpesa_receipt_number: mpesaCode,
                    amount: amount,
                    phone_e164: phone
                })
            });

            const data = await response.json();

            if (data.success) {
                showSubmissionSuccess();
                // Clear form
                document.getElementById('mpesa_receipt_code').value = '';
                document.getElementById('phone_input').value = '';
            } else {
                throw new Error(data.message || 'Submission failed');
            }

        } catch (error) {
            console.error('Submission error:', error);
            showSubmissionError(error.message || 'Failed to submit M-PESA code. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit for Verification';
        }
    }

    function showSubmissionSuccess() {
        document.getElementById('submission_success').classList.remove('d-none');
        document.getElementById('submission_error').classList.add('d-none');
        document.getElementById('submit_code_btn').classList.add('d-none');
    }

    function showSubmissionError(message) {
        document.getElementById('submission_error_text').textContent = message;
        document.getElementById('submission_error').classList.remove('d-none');
        document.getElementById('submission_success').classList.add('d-none');
    }

    function clearSubmissionError() {
        document.getElementById('submission_error').classList.add('d-none');
        document.getElementById('submission_success').classList.add('d-none');
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        }).catch(() => {
            alert('Failed to copy. Please copy manually.');
        });
    }
</script>
@endsection
