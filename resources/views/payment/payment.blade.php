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
                        <!-- Phone Number Input -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Your Phone Number *</label>
                            <input 
                                type="tel" 
                                id="phone_input" 
                                class="form-control form-control-lg"
                                placeholder="0712345678 or +254712345678"
                            />
                            <small class="text-muted">We'll send the M-PESA payment prompt to this number</small>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <div class="card border-info">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            id="method_stk" 
                                            name="payment_method" 
                                            value="stk" 
                                            checked
                                        />
                                        <label class="form-check-label w-100" for="method_stk">
                                            <strong>M-PESA STK Push (Recommended)</strong>
                                            <br/>
                                            <small class="text-muted">Automatic payment prompt will be sent to your phone. Instant confirmation.</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-success mt-3">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            id="method_paybill" 
                                            name="payment_method" 
                                            value="paybill"
                                        />
                                        <label class="form-check-label w-100" for="method_paybill">
                                            <strong>M-PESA Paybill / Till</strong>
                                            <br/>
                                            <small class="text-muted">Use the booking reference as Account Number. We will auto-confirm from the M-PESA callback.</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pay Button -->
                        <button 
                            id="pay_now_btn" 
                            onclick="handlePaymentMethod()"
                            class="btn btn-primary btn-lg w-100 fw-bold"
                        >
                            <i class="fas fa-mobile-alt me-2"></i>
                            Pay {{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}
                        </button>
                    </div>

                    <!-- Loading State -->
                    <div id="loading_state" class="card-body text-center d-none">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted" id="loading_message">Sending M-PESA prompt...</p>
                    </div>

                    <!-- Success State -->
                    <div id="success_state" class="card-body text-center d-none">
                        <div class="mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="text-success mb-2">Payment Received!</h4>
                        <p class="text-muted mb-3" id="success_message">Your payment has been verified. Receipt sent to your email.</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('payment.receipt-download', ['booking' => $booking->id]) }}" class="btn btn-success">
                                <i class="fas fa-download me-1"></i> Download Receipt
                            </a>
                            <a href="/" class="btn btn-primary">← Back to Home</a>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="error_state" class="card-body d-none">
                        <div class="alert alert-danger mb-0">
                            <h5 class="mb-2" id="error_title">Payment Failed</h5>
                            <p class="mb-2" id="error_message">An error occurred</p>
                            <button onclick="resetForm()" class="btn btn-sm btn-outline-danger">Try Again</button>
                        </div>
                    </div>

                    <!-- Pending State -->
                    <div id="pending_state" class="card-body d-none">
                        <div class="alert alert-info">
                            <h5 class="mb-2">
                                <i class="fas fa-clock me-2"></i>
                                Awaiting Payment Confirmation
                            </h5>
                            <p class="mb-2">Please complete your Paybill payment using:</p>
                            <ul class="mb-2">
                                <li><strong>Paybill Number:</strong> {{ config('mpesa.business_shortcode', '174379') }}</li>
                                <li><strong>Account Number:</strong> {{ $booking->booking_ref }}</li>
                                <li><strong>Amount:</strong> {{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}</li>
                            </ul>
                            <p class="mb-0">Your payment will be automatically confirmed within seconds after you complete the transaction.</p>
                        </div>
                    </div>

                    <!-- Manual Fallback Section -->
                    <div id="manual_section" class="card-body border-top d-none">
                        <div class="alert alert-warning mb-4">
                            <h5 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Payment Prompt Failed
                            </h5>
                            <p class="mb-0 mt-2">The automatic M-PESA prompt didn't work. Please pay manually via Paybill using the details below:</p>
                        </div>

                        <!-- Paybill Information -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">Paybill Payment Details</h6>
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
                                        <small class="text-muted">Enter this as the Account Number to link your payment automatically.</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <small class="text-muted">Company:</small>
                                        <p class="mb-0 fw-bold">{{ config('mpesa.company_name', 'Holiday Rentals') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">How to Pay via M-PESA Paybill:</h6>
                                <ol class="mb-3">
                                    <li>Open M-PESA on your phone</li>
                                    <li>Select <strong>"Lipa na M-PESA"</strong> then <strong>"Pay Bill"</strong></li>
                                    <li>Enter Business Number (Paybill): <code>{{ config('mpesa.business_shortcode', '174379') }}</code></li>
                                    <li>Enter Account Number: <code>{{ $booking->booking_ref }}</code></li>
                                    <li>Enter Amount: <strong>{{ number_format($booking->amount_due, 2) }}</strong></li>
                                    <li>Enter your M-PESA PIN</li>
                                    <li>You'll receive a confirmation SMS with a receipt code.</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Manual Receipt Code Entry -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">Enter Your M-PESA Receipt Code</h6>
                                <p class="text-muted mb-3">After completing the Paybill payment, enter the M-PESA receipt code you received in the SMS below:</p>
                                <div class="mb-3">
                                    <input 
                                        type="text" 
                                        id="mpesa_receipt_code" 
                                        class="form-control form-control-lg"
                                        placeholder="e.g. LIK123ABC456"
                                        maxlength="20"
                                    />
                                    <small class="text-muted">This is the code from your M-PESA confirmation SMS</small>
                                </div>
                                <button 
                                    id="validate_receipt_btn" 
                                    onclick="validateReceiptCode()"
                                    class="btn btn-primary w-100 fw-bold"
                                >
                                    <i class="fas fa-check me-2"></i>
                                    Validate Payment
                                </button>
                            </div>
                        </div>

                        <!-- Validation Result -->
                        <div id="validation_success" class="alert alert-success d-none">
                            <h5 class="mb-2"><i class="fas fa-check-circle me-2"></i>Payment Confirmed!</h5>
                            <p id="validation_success_text" class="mb-0"></p>
                        </div>

                        <div id="validation_error" class="alert alert-danger d-none">
                            <h5 class="mb-2"><i class="fas fa-exclamation-circle me-2"></i>Validation Failed</h5>
                            <p id="validation_error_text" class="mb-0"></p>
                            <button onclick="clearValidationError()" class="btn btn-sm btn-outline-danger mt-2">Try Again</button>
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
    
    let paymentIntentId = null;
    let stkPollingInterval = null;
    let statusPollingInterval = null;

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

            // STK successful - poll for status
            updateLoadingMessage('Waiting for payment confirmation...');
            pollPaymentStatus();

        } catch (error) {
            console.error('Error:', error);
            showError(error.message || 'An error occurred. Please try again.');
        }
    }

    // Poll for payment status
    function pollPaymentStatus() {
        const maxAttempts = 30;
        let attempts = 0;

        stkPollingInterval = setInterval(async () => {
            attempts++;

            try {
                const response = await fetch(`/payment/intents/${paymentIntentId}`);
                const data = await response.json();

                if (data.data.status === 'SUCCEEDED') {
                    clearInterval(stkPollingInterval);
                    showSuccess('Payment confirmed! Receipt sent to your email.');
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 3000);
                    return;
                } else if (data.data.status === 'FAILED') {
                    clearInterval(stkPollingInterval);
                    showManualFallback();
                    return;
                }

                if (attempts >= maxAttempts) {
                    clearInterval(stkPollingInterval);
                    showManualFallback();
                    return;
                }
            } catch (error) {
                console.error('Error checking status:', error);
            }
        }, 2000);
    }

    // Poll booking status for C2B
    function startStatusPolling() {
        clearInterval(statusPollingInterval);
        const maxAttempts = 120; // ~10 minutes at 5s interval
        let attempts = 0;

        statusPollingInterval = setInterval(async () => {
            attempts++;
            try {
                const response = await fetch(`/api/booking/${bookingRef}/status`);
                const data = await response.json();

                if (!data.success) {
                    return;
                }

                const status = data.data.status;
                const paid = data.data.amount_paid;
                const receiptNo = data.data.last_receipt?.receipt_no;
                const mpesaReceipt = data.data.last_receipt?.mpesa_receipt_number;

                if (status === 'PAID') {
                    clearInterval(statusPollingInterval);
                    const receiptText = mpesaReceipt ? ` Receipt: ${mpesaReceipt}` : '';
                    showSuccess(`Payment confirmed for ${bookingRef}.${receiptText}`);
                    return;
                }

                if (status === 'PARTIALLY_PAID') {
                    updateLoadingMessage(`Waiting for payment confirmation... Paid ${paid} / ${amount} ${currency}`);
                }

                if (attempts >= maxAttempts) {
                    clearInterval(statusPollingInterval);
                    showManualFallback();
                }
            } catch (error) {
                console.error('Polling error', error);
            }
        }, 5000);
    }

    // Show Manual Fallback
    function showManualFallback() {
        document.getElementById('payment-container').classList.add('d-none');
        document.getElementById('loading_state').classList.add('d-none');
        document.getElementById('success_state').classList.add('d-none');
        document.getElementById('error_state').classList.add('d-none');
        document.getElementById('pending_state').classList.add('d-none');
        document.getElementById('manual_section').classList.remove('d-none');
    }

    function showPendingState() {
        document.getElementById('payment-container').classList.add('d-none');
        document.getElementById('loading_state').classList.remove('d-none');
        document.getElementById('success_state').classList.add('d-none');
        document.getElementById('error_state').classList.add('d-none');
        document.getElementById('pending_state').classList.add('d-none');
        document.getElementById('manual_section').classList.add('d-none');
        updateLoadingMessage('Waiting for payment confirmation... Complete Paybill using your booking reference.');
    }



    // UI Helper Functions
    function showLoading(message = 'Processing...') {
        document.getElementById('payment-container').classList.add('d-none');
        document.getElementById('success_state').classList.add('d-none');
        document.getElementById('error_state').classList.add('d-none');
        document.getElementById('pending_state').classList.add('d-none');
        document.getElementById('manual_section').classList.add('d-none');
        document.getElementById('loading_state').classList.remove('d-none');
        updateLoadingMessage(message);
    }

    function updateLoadingMessage(message) {
        document.getElementById('loading_message').textContent = message;
    }

    function showSuccess(message) {
        document.getElementById('payment-container').classList.add('d-none');
        document.getElementById('loading_state').classList.add('d-none');
        document.getElementById('error_state').classList.add('d-none');
        document.getElementById('pending_state').classList.add('d-none');
        document.getElementById('manual_section').classList.add('d-none');
        document.getElementById('success_message').textContent = message;
        document.getElementById('success_state').classList.remove('d-none');
    }

    function showError(message) {
        document.getElementById('payment-container').classList.remove('d-none');
        document.getElementById('loading_state').classList.add('d-none');
        document.getElementById('success_state').classList.add('d-none');
        document.getElementById('pending_state').classList.add('d-none');
        document.getElementById('manual_section').classList.add('d-none');
        document.getElementById('error_title').textContent = 'Error';
        document.getElementById('error_message').textContent = message;
        document.getElementById('error_state').classList.remove('d-none');
    }

    function resetForm() {
        document.getElementById('payment-container').classList.remove('d-none');
        document.getElementById('loading_state').classList.add('d-none');
        document.getElementById('success_state').classList.add('d-none');
        document.getElementById('error_state').classList.add('d-none');
        document.getElementById('pending_state').classList.add('d-none');
        document.getElementById('manual_section').classList.add('d-none');
        document.getElementById('phone_input').value = '';
        document.getElementById('receipt_number').value = '';
        document.getElementById('notes').value = '';
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        });
    }

    // Validate M-PESA receipt code for C2B
    async function validateReceiptCode() {
        const receiptCode = document.getElementById('mpesa_receipt_code').value.trim();

        if (!receiptCode) {
            showValidationError('Please enter your M-PESA receipt code');
            return;
        }

        document.getElementById('validate_receipt_btn').disabled = true;
        document.getElementById('validate_receipt_btn').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Validating...';

        try {
            const response = await fetch(`/api/booking/${bookingRef}/status`, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json();

            if (data.success && data.data.status === 'PAID') {
                showValidationSuccess(`Payment confirmed! Receipt: ${data.data.last_receipt?.mpesa_receipt_number || 'Processing'}`);
                clearInterval(statusPollingInterval);
                setTimeout(() => {
                    window.location.href = '/';
                }, 3000);
            } else if (data.success && data.data.status === 'PARTIALLY_PAID') {
                showValidationError(`Partial payment received. Paid: ${data.data.amount_paid}/${data.data.total_amount} KES. Please complete the remaining amount.`);
            } else {
                showValidationError('Payment not yet confirmed. Please wait a moment and try again.');
            }
        } catch (error) {
            showValidationError('Failed to validate payment. Please try again.');
            console.error('Validation error:', error);
        } finally {
            document.getElementById('validate_receipt_btn').disabled = false;
            document.getElementById('validate_receipt_btn').innerHTML = '<i class="fas fa-check me-2"></i>Validate Payment';
        }
    }

    function showValidationSuccess(message) {
        document.getElementById('validation_success_text').textContent = message;
        document.getElementById('validation_success').classList.remove('d-none');
        document.getElementById('validation_error').classList.add('d-none');
    }

    function showValidationError(message) {
        document.getElementById('validation_error_text').textContent = message;
        document.getElementById('validation_error').classList.remove('d-none');
        document.getElementById('validation_success').classList.add('d-none');
    }

    function clearValidationError() {
        document.getElementById('mpesa_receipt_code').value = '';
        document.getElementById('validation_error').classList.add('d-none');
        document.getElementById('validation_success').classList.add('d-none');
    }
</script>
@endsection
