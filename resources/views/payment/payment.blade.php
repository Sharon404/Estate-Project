@extends('layouts.velzon.app')

@section('title', 'Payment - ' . $booking->booking_ref)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
            <p class="text-gray-600">Booking Reference: <span class="font-semibold text-blue-600">{{ $booking->booking_ref }}</span></p>
        </div>

        <!-- Main Payment Container -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Booking Summary -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-blue-100 text-sm">Property</p>
                        <p class="text-lg font-semibold">{{ $booking->property->name }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">Check-in</p>
                        <p class="text-lg font-semibold">{{ $booking->check_in->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">Check-out</p>
                        <p class="text-lg font-semibold">{{ $booking->check_out->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-blue-100 text-sm">Duration</p>
                        <p class="text-lg font-semibold">{{ $booking->nights }} nights</p>
                    </div>
                </div>
            </div>

            <!-- Payment Amount Section -->
            <div class="bg-gray-50 p-6 border-b">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-700">Total Amount Due:</span>
                    <span class="text-3xl font-bold text-blue-600">{{ number_format($booking->total_amount, 2) }} {{ $booking->currency }}</span>
                </div>
                @if($booking->status === 'PARTIALLY_PAID')
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-700">Already Paid:</span>
                        <span class="text-2xl font-semibold text-green-600">{{ number_format($booking->amount_paid, 2) }} {{ $booking->currency }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Remaining Due:</span>
                        <span class="text-2xl font-bold text-red-600">{{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}</span>
                    </div>
                @endif
            </div>

            <!-- Payment Form -->
            <div class="p-8">
                <div id="payment-container">
                    <!-- Phone Number Input -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Your Phone Number *</label>
                        <div class="flex gap-2">
                            <input 
                                type="tel" 
                                id="phone_input" 
                                placeholder="0712345678 or +254712345678"
                                class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            />
                        </div>
                        <p class="text-gray-500 text-xs mt-2">We'll send the M-PESA payment prompt to this number</p>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="mb-6">
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <input 
                                    type="radio" 
                                    id="method_stk" 
                                    name="payment_method" 
                                    value="stk" 
                                    checked
                                    class="mt-1"
                                />
                                <label for="method_stk" class="ml-3 flex-1 cursor-pointer">
                                    <p class="font-semibold text-gray-900">M-PESA STK Push (Recommended)</p>
                                    <p class="text-sm text-gray-600 mt-1">Automatic payment prompt will be sent to your phone. Instant confirmation.</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <button 
                        id="pay_now_btn" 
                        onclick="handlePaymentMethod()"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-4 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-300 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                        Pay {{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}
                    </button>
                </div>

                <!-- Loading State -->
                <div id="loading_state" class="hidden">
                    <div class="text-center py-8">
                        <div class="inline-block animate-spin">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 mt-4 text-lg" id="loading_message">Sending M-PESA prompt...</p>
                    </div>
                </div>

                <!-- Success State -->
                <div id="success_state" class="hidden">
                    <div class="bg-green-50 border-2 border-green-200 rounded-lg p-6 text-center">
                        <svg class="w-16 h-16 text-green-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-green-700 mb-2">Payment Received!</h3>
                        <p class="text-gray-600 mb-4" id="success_message">Your payment has been verified. Receipt sent to your email.</p>
                        <a href="/" class="text-blue-600 font-semibold hover:underline">← Back to Home</a>
                    </div>
                </div>

                <!-- Error State -->
                <div id="error_state" class="hidden">
                    <div class="bg-red-50 border-2 border-red-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-red-800 mb-2" id="error_title">Payment Failed</h3>
                                <p class="text-red-700 mb-4" id="error_message">An error occurred</p>
                                <button onclick="resetForm()" class="text-blue-600 font-semibold hover:underline">Try Again</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending State (Under Review) -->
                <div id="pending_state" class="hidden">
                    <div class="bg-amber-50 border-2 border-amber-200 rounded-lg p-6">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-amber-600 mt-0.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-semibold text-amber-800 mb-2">Payment Under Review</h3>
                                <p class="text-amber-700 mb-2">Your receipt has been submitted for verification.</p>
                                <p class="text-amber-700 mb-4">
                                    <strong>Receipt:</strong> <span id="pending_receipt" class="font-mono"></span>
                                </p>
                                <p class="text-amber-700">Our team will verify your payment within 24 hours. You'll receive an email confirmation.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Fallback Section -->
            <div id="manual_section" class="hidden border-t">
                <div class="bg-gray-50 p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚠️ Payment Prompt Failed</h3>
                    <p class="text-gray-600 mb-4">The automatic M-PESA prompt didn't work. Please pay manually using the details below:</p>
                </div>

                <div class="p-8">
                    <!-- Till Information -->
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-6 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">Payment Details</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Till Number:</span>
                                <div class="flex items-center gap-2">
                                    <code class="bg-white px-3 py-2 rounded font-mono font-bold text-blue-600">{{ config('mpesa.till_number', '*138#') }}</code>
                                    <button onclick="copyToClipboard('{{ config('mpesa.till_number', '*138#') }}')" class="text-blue-600 font-semibold text-sm hover:underline">Copy</button>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Amount:</span>
                                <span class="font-semibold">{{ number_format($booking->amount_due, 2) }} {{ $booking->currency }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Company:</span>
                                <span class="font-semibold">{{ config('mpesa.company_name', 'Nairobi Homes') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                        <h4 class="font-semibold text-gray-900 mb-4">How to Pay:</h4>
                        <ol class="space-y-2 text-gray-700">
                            <li class="flex">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-blue-600 text-white text-sm font-bold mr-3">1</span>
                                <span>Open M-PESA on your phone</span>
                            </li>
                            <li class="flex">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-blue-600 text-white text-sm font-bold mr-3">2</span>
                                <span>Select "Lipa na M-Pesa Online"</span>
                            </li>
                            <li class="flex">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-blue-600 text-white text-sm font-bold mr-3">3</span>
                                <span>Enter till: <code class="bg-white px-2 py-1 rounded font-mono font-bold">{{ config('mpesa.till_number', '*138#') }}</code></span>
                            </li>
                            <li class="flex">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-blue-600 text-white text-sm font-bold mr-3">4</span>
                                <span>Enter amount: {{ number_format($booking->amount_due, 2) }}</span>
                            </li>
                            <li class="flex">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-blue-600 text-white text-sm font-bold mr-3">5</span>
                                <span>Enter your M-PESA PIN</span>
                            </li>
                            <li class="flex">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-blue-600 text-white text-sm font-bold mr-3">6</span>
                                <span>You'll get a receipt code (e.g., LIK123ABC456)</span>
                            </li>
                        </ol>
                    </div>

                    <!-- Manual Receipt Form -->
                    <form id="manual_form" onsubmit="submitManualPayment(event)">
                        <h4 class="font-semibold text-gray-900 mb-4">Enter Receipt Details</h4>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">M-PESA Receipt Number *</label>
                            <input 
                                type="text" 
                                id="receipt_number" 
                                placeholder="e.g., LIK123ABC456"
                                pattern="[A-Z0-9]{9,20}"
                                required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            />
                            <p class="text-gray-500 text-xs mt-2">Format: 9-20 uppercase letters and numbers (e.g., LIK123ABC456)</p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Amount Paid *</label>
                            <input 
                                type="number" 
                                value="{{ $booking->amount_due }}" 
                                readonly
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes (optional)</label>
                            <textarea 
                                id="notes" 
                                placeholder="e.g., Payment from savings..."
                                maxlength="500"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                rows="3"
                            ></textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-300"
                        >
                            Submit Receipt for Verification
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-semibold text-gray-800 mb-1">Contact Support</p>
                    <p class="text-gray-600">Email: support@nairobi-homes.com</p>
                    <p class="text-gray-600">Phone: +254 (0) 123 456 789</p>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 mb-1">Payment Information</p>
                    <p class="text-gray-600">We accept M-PESA payments</p>
                    <p class="text-gray-600">All payments are secured and encrypted</p>
                </div>
            </div>
        </div>
    </div>
</div>

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

            // Initiate STK
            const stkResponse = await fetch('/payment/mpesa/stk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntentId,
                    phone_number: phone
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
        const maxAttempts = 30; // 60 seconds (2 second interval)
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
        resetForm();
        document.getElementById('manual_section').classList.remove('hidden');
        document.getElementById('payment-container').classList.add('hidden');
        document.getElementById('loading_state').classList.add('hidden');
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
            document.getElementById('pending_state').classList.remove('hidden');
            document.getElementById('loading_state').classList.add('hidden');
            document.getElementById('manual_section').classList.add('hidden');

        } catch (error) {
            console.error('Error:', error);
            showError(error.message || 'Failed to submit receipt');
        }
    }

    // UI Helper Functions
    function showLoading(message = 'Processing...') {
        document.getElementById('payment-container').classList.add('hidden');
        document.getElementById('success_state').classList.add('hidden');
        document.getElementById('error_state').classList.add('hidden');
        document.getElementById('pending_state').classList.add('hidden');
        document.getElementById('manual_section').classList.add('hidden');
        document.getElementById('loading_state').classList.remove('hidden');
        updateLoadingMessage(message);
    }

    function updateLoadingMessage(message) {
        document.getElementById('loading_message').textContent = message;
    }

    function showSuccess(message) {
        document.getElementById('payment-container').classList.add('hidden');
        document.getElementById('loading_state').classList.add('hidden');
        document.getElementById('error_state').classList.add('hidden');
        document.getElementById('pending_state').classList.add('hidden');
        document.getElementById('manual_section').classList.add('hidden');
        document.getElementById('success_message').textContent = message;
        document.getElementById('success_state').classList.remove('hidden');
    }

    function showError(message) {
        document.getElementById('payment-container').classList.remove('hidden');
        document.getElementById('loading_state').classList.add('hidden');
        document.getElementById('success_state').classList.add('hidden');
        document.getElementById('pending_state').classList.add('hidden');
        document.getElementById('manual_section').classList.add('hidden');
        document.getElementById('error_title').textContent = 'Error';
        document.getElementById('error_message').textContent = message;
        document.getElementById('error_state').classList.remove('hidden');
    }

    function resetForm() {
        document.getElementById('payment-container').classList.remove('hidden');
        document.getElementById('loading_state').classList.add('hidden');
        document.getElementById('success_state').classList.add('hidden');
        document.getElementById('error_state').classList.add('hidden');
        document.getElementById('pending_state').classList.add('hidden');
        document.getElementById('manual_section').classList.add('hidden');
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
