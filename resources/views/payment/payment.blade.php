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
                        <a href="/" class="btn btn-primary">← Back to Home</a>
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
                        <div class="alert alert-warning">
                            <h5 class="mb-2">
                                <i class="fas fa-hourglass-half me-2"></i>
                                Payment Under Review
                            </h5>
                            <p class="mb-2">Your receipt has been submitted for verification.</p>
                            <p class="mb-2"><strong>Receipt:</strong> <code id="pending_receipt"></code></p>
                            <p class="mb-0">Our team will verify your payment within 24 hours. You'll receive an email confirmation.</p>
                        </div>
                    </div>

                    <!-- Manual Fallback Section -->
                    <div id="manual_section" class="card-body border-top d-none">
                        <div class="alert alert-warning mb-4">
                            <h5 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Payment Prompt Failed
                            </h5>
                            <p class="mb-0 mt-2">The automatic M-PESA prompt didn't work. Please pay manually using the details below:</p>
                        </div>

                        <!-- Till Information -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">Payment Details</h6>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <small class="text-muted">Till Number:</small>
                                        <div class="d-flex gap-2 align-items-center">
                                            <code class="bg-white px-2 py-1 rounded fw-bold text-primary">{{ config('mpesa.till_number', '*138#') }}</code>
                                            <button onclick="copyToClipboard('{{ config('mpesa.till_number', '*138#') }}')" class="btn btn-sm btn-link" title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted">Amount:</small>
                                        <p class="mb-0 fw-bold">{{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <small class="text-muted">Company:</small>
                                        <p class="mb-0 fw-bold">{{ config('mpesa.company_name', 'Nairobi Homes') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3 fw-bold">How to Pay:</h6>
                                <ol class="mb-0">
                                    <li>Open M-PESA on your phone</li>
                                    <li>Select "Lipa na M-Pesa Online"</li>
                                    <li>Enter till: <code>{{ config('mpesa.till_number', '*138#') }}</code></li>
                                    <li>Enter amount: {{ number_format($booking->amount_due, 2) }}</li>
                                    <li>Enter your M-PESA PIN</li>
                                    <li>You'll get a receipt code (e.g., LIK123ABC456)</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Manual Receipt Form -->
                        <form id="manual_form" onsubmit="submitManualPayment(event)">
                            <h6 class="mb-3 fw-bold">Enter Receipt Details</h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">M-PESA Receipt Number *</label>
                                <input 
                                    type="text" 
                                    id="receipt_number" 
                                    class="form-control"
                                    placeholder="e.g., LIK123ABC456"
                                    pattern="[A-Z0-9]{9,20}"
                                    required
                                />
                                <small class="text-muted">Format: 9-20 uppercase letters and numbers</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Amount Paid *</label>
                                <input 
                                    type="text" 
                                    value="{{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}" 
                                    class="form-control"
                                    readonly
                                    disabled
                                />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Additional Notes (optional)</label>
                                <textarea 
                                    id="notes" 
                                    class="form-control"
                                    placeholder="e.g., Any additional information..."
                                    maxlength="500"
                                    rows="3"
                                ></textarea>
                            </div>

                            <button 
                                type="submit" 
                                class="btn btn-primary btn-lg w-100 fw-bold"
                            >
                                <i class="fas fa-check me-2"></i>
                                Submit Receipt for Verification
                            </button>
                        </form>
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
                                    Email: support@nairobi-homes.com<br/>
                                    Phone: +254 (0) 123 456 789
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1">Payment Information</h6>
                                <p class="text-muted mb-0">
                                    We accept M-PESA payments<br/>
                                    All payments are secured and encrypted
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
    const amount = {{ $booking->amount_due }};
    const currency = '{{ $booking->currency }}';
    const till = '{{ config("mpesa.till_number", "*138#") }}';
    
    let paymentIntentId = null;
    let stkPollingInterval = null;

    // Handle Payment Method
    async function handlePaymentMethod() {
        const phone = document.getElementById('phone_input').value.trim();
        
        if (!phone) {
            showError('Please enter your phone number');
            return;
        }

        try {
            // Show loading state
            showLoading('Sending M-PESA prompt to your phone...');

            // Create payment intent
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

            const intentData = await intentResponse.json();
            
            if (!intentData.success) {
                throw new Error(intentData.message || 'Failed to create payment intent');
            }

            paymentIntentId = intentData.data.payment_intent_id;

            // Normalize phone to E.164 for M-PESA
            const phoneE164 = phone.startsWith('+') ? phone : `+254${phone.replace(/^0/, '')}`;

            // Initiate STK
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

            const stkData = await stkResponse.json();

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

                if (data.data.status === 'SUCCESS') {
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

    // Show Manual Fallback
    function showManualFallback() {
        document.getElementById('payment-container').classList.add('d-none');
        document.getElementById('loading_state').classList.add('d-none');
        document.getElementById('success_state').classList.add('d-none');
        document.getElementById('error_state').classList.add('d-none');
        document.getElementById('pending_state').classList.add('d-none');
        document.getElementById('manual_section').classList.remove('d-none');
    }

    // Submit Manual Payment
    async function submitManualPayment(event) {
        event.preventDefault();

        const receipt = document.getElementById('receipt_number').value.toUpperCase().trim();
        const notes = document.getElementById('notes').value.trim();

        if (!receipt.match(/^[A-Z0-9]{9,20}$/)) {
            showError('Invalid receipt format. Use 9-20 alphanumeric characters (e.g., LIK123ABC456)');
            return;
        }

        try {
            showLoading('Submitting receipt for verification...');

            const response = await fetch('/payment/manual-entry', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntentId,
                    mpesa_receipt_number: receipt,
                    amount: amount,
                    notes: notes || null
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message || 'Failed to submit payment');
            }

            // Show pending state
            document.getElementById('pending_receipt').textContent = receipt;
            document.getElementById('pending_state').classList.remove('d-none');
            document.getElementById('loading_state').classList.add('d-none');
            document.getElementById('manual_section').classList.add('d-none');

        } catch (error) {
            console.error('Error:', error);
            showError(error.message || 'Failed to submit receipt');
        }
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
            alert('Till number copied to clipboard!');
        });
    }
</script>
@endsection
