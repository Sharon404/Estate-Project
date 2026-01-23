# M-PESA Payment Frontend Implementation

## Quick Overview

Your backend supports **two payment methods**:
1. **STK Push** - Automatic (try first)
2. **Manual Entry** - Fallback (if STK fails)

---

## Frontend Components Needed

### 1. Payment Intent Creation

```javascript
// Step 1: Create payment intent when user visits payment screen
async function createPaymentIntent(bookingId, amount = null) {
  try {
    const response = await fetch('/payment/intents', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        booking_id: bookingId,
        amount: amount || null
      })
    });

    const data = await response.json();
    
    if (data.success) {
      return data.data; // Returns: { payment_intent_id, amount, status, ... }
    } else {
      showError(data.message);
      return null;
    }
  } catch (error) {
    showError('Failed to create payment intent');
    console.error(error);
    return null;
  }
}
```

### 2. STK Push Initiation

```javascript
// Step 2: Send STK to user's phone
async function initiateSTKPayment(paymentIntentId, phoneNumber) {
  try {
    // Format phone: 0712345678 or +254712345678
    const formattedPhone = formatPhoneNumber(phoneNumber);

    const response = await fetch('/payment/mpesa/stk', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        payment_intent_id: paymentIntentId,
        phone_number: formattedPhone
      })
    });

    const data = await response.json();
    
    if (data.success) {
      // STK sent successfully
      showMessage(`STK prompt sent to ${formattedPhone}`);
      
      // Wait for callback or show fallback
      pollPaymentStatus(paymentIntentId);
      
      return true;
    } else {
      // STK failed - offer manual payment
      showWarning(data.message);
      showManualPaymentFallback(paymentIntentId);
      return false;
    }
  } catch (error) {
    // Network error - offer manual payment
    showError('Cannot reach M-PESA service');
    showManualPaymentFallback(paymentIntentId);
    return false;
  }
}

// Helper: Format phone to standard format
function formatPhoneNumber(phone) {
  // Remove common separators
  const cleaned = phone.replace(/[\s\-\(\)]/g, '');
  
  // Convert to 0712345678 format if needed
  if (cleaned.startsWith('+254')) {
    return cleaned.replace('+254', '0');
  }
  return cleaned;
}

// Helper: Poll for payment completion
function pollPaymentStatus(paymentIntentId, maxAttempts = 30) {
  let attempts = 0;
  
  const interval = setInterval(async () => {
    attempts++;
    
    const response = await fetch(`/payment/status/${paymentIntentId}`);
    const data = await response.json();
    
    if (data.data.status === 'SUCCESS') {
      clearInterval(interval);
      showSuccess('Payment received! Receipt sent to your email.');
      redirectToBookingConfirmation();
    } else if (data.data.status === 'FAILED') {
      clearInterval(interval);
      showWarning('Payment failed. Please try manual payment.');
      showManualPaymentFallback(paymentIntentId);
    } else if (attempts >= maxAttempts) {
      // Timeout - offer manual fallback
      clearInterval(interval);
      showWarning('STK timeout. Please use manual payment method.');
      showManualPaymentFallback(paymentIntentId);
    }
  }, 2000); // Check every 2 seconds
}
```

### 3. Manual Payment Fallback

```javascript
// When STK fails or times out
function showManualPaymentFallback(paymentIntentId, bookingAmount) {
  const till = '*138#'; // Or load from config
  const company = 'Nairobi Homes'; // Or load from config
  
  const html = `
    <div class="manual-payment-fallback">
      <div class="alert alert-info">
        <h3>M-PESA Prompt Failed</h3>
        <p>Please pay manually using the details below:</p>
      </div>
      
      <div class="payment-details">
        <div class="detail-row">
          <label>Till Number:</label>
          <strong>${till}</strong>
          <button onclick="copyToClipboard('${till}')" class="btn-small">Copy</button>
        </div>
        
        <div class="detail-row">
          <label>Amount:</label>
          <strong>${bookingAmount} KES</strong>
        </div>
        
        <div class="detail-row">
          <label>Company:</label>
          <strong>${company}</strong>
        </div>
      </div>
      
      <div class="payment-instructions">
        <h4>How to Pay:</h4>
        <ol>
          <li>Open M-PESA on your phone</li>
          <li>Tap "Lipa na M-Pesa Online"</li>
          <li>Select "Lipa na M-Pesa Online"</li>
          <li>Enter till: <strong>${till}</strong></li>
          <li>Enter amount: <strong>${bookingAmount}</strong></li>
          <li>Enter your M-PESA PIN</li>
          <li>You'll get a receipt (e.g., LIK123ABC456)</li>
        </ol>
      </div>
      
      <form id="manual-payment-form">
        <div class="form-group">
          <label for="receipt">M-PESA Receipt Number *</label>
          <input 
            type="text" 
            id="receipt" 
            name="receipt" 
            placeholder="e.g., LIK123ABC456"
            pattern="[A-Z0-9]{9,20}"
            required
          />
          <small>Format: 9-20 characters, uppercase letters and numbers</small>
        </div>
        
        <div class="form-group">
          <label for="amount">Amount Paid *</label>
          <input 
            type="number" 
            id="amount" 
            name="amount" 
            value="${bookingAmount}"
            readonly
          />
        </div>
        
        <div class="form-group">
          <label for="phone">Your Phone Number (optional)</label>
          <input 
            type="tel" 
            id="phone" 
            name="phone" 
            placeholder="+254712345678"
          />
        </div>
        
        <div class="form-group">
          <label for="notes">Additional Notes (optional)</label>
          <textarea 
            id="notes" 
            name="notes" 
            placeholder="e.g., Payment from savings..."
            maxlength="500"
          ></textarea>
        </div>
        
        <button 
          type="submit" 
          class="btn btn-primary"
          onclick="submitManualPayment(${paymentIntentId})"
        >
          Submit Receipt for Review
        </button>
      </form>
      
      <div class="help-text">
        <p><strong>What happens next?</strong></p>
        <p>Our team will verify your payment within 24 hours. You'll receive an email once your payment is confirmed.</p>
      </div>
    </div>
  `;
  
  document.getElementById('payment-container').innerHTML = html;
}

// Helper: Copy till number to clipboard
function copyToClipboard(text) {
  navigator.clipboard.writeText(text);
  showSuccess('Till number copied!');
}
```

### 4. Manual Payment Submission

```javascript
// Submit receipt number for admin verification
async function submitManualPayment(paymentIntentId) {
  const receipt = document.getElementById('receipt').value.toUpperCase();
  const amount = document.getElementById('amount').value;
  const phone = document.getElementById('phone').value;
  const notes = document.getElementById('notes').value;
  
  // Validate receipt format
  if (!receipt.match(/^[A-Z0-9]{9,20}$/)) {
    showError('Invalid receipt format. Use 9-20 alphanumeric characters.');
    return;
  }
  
  try {
    const response = await fetch('/payment/manual-entry', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        payment_intent_id: paymentIntentId,
        mpesa_receipt_number: receipt,
        amount: parseFloat(amount),
        phone_e164: phone || null,
        notes: notes || null
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      showSuccess(data.message);
      showPaymentPending(data.data);
    } else {
      showError(data.message);
    }
  } catch (error) {
    showError('Failed to submit payment. Please try again.');
    console.error(error);
  }
}

// Show pending status
function showPaymentPending(submission) {
  const html = `
    <div class="alert alert-success">
      <h3>✓ Receipt Submitted</h3>
      <p>Receipt: <strong>${submission.receipt_number}</strong></p>
      <p>Amount: <strong>${submission.amount} KES</strong></p>
      <p>Status: <strong>${submission.status}</strong></p>
      <hr/>
      <p>${submission.next_step}</p>
      <p style="margin-top: 20px; font-size: 0.9em; color: #666;">
        Submission ID: ${submission.submission_id}
      </p>
    </div>
  `;
  
  document.getElementById('payment-container').innerHTML = html;
}
```

### 5. Payment Status Checking

```javascript
// Check current payment status
async function checkPaymentStatus(paymentIntentId) {
  try {
    const response = await fetch(`/payment/status/${paymentIntentId}`);
    const data = await response.json();
    
    const status = data.data.status; // SUCCESS, FAILED, PENDING, etc.
    
    switch (status) {
      case 'SUCCESS':
        showSuccess('Payment confirmed!');
        redirectToConfirmation();
        break;
        
      case 'FAILED':
        showError('Payment failed. Please try again.');
        showRetryOptions(paymentIntentId);
        break;
        
      case 'PENDING':
        showInfo('Payment is being processed...');
        setTimeout(() => checkPaymentStatus(paymentIntentId), 3000);
        break;
        
      case 'UNDER_REVIEW':
        showInfo('Receipt submitted. Admin will verify within 24 hours.');
        showSubmissionDetails(data.data);
        break;
    }
  } catch (error) {
    console.error('Failed to check status:', error);
  }
}
```

---

## Complete Payment Flow (HTML/JS)

```html
<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <style>
        .payment-section { max-width: 500px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input,
        .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-primary { background: #4CAF50; color: white; }
        .btn-primary:hover { background: #45a049; }
        .alert { padding: 15px; margin-bottom: 15px; border-radius: 4px; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .payment-details { background: #f9f9f9; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .detail-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .detail-row label { font-weight: bold; }
        .btn-small { padding: 5px 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="payment-section">
        <h2>Payment</h2>
        <div id="payment-container">
            <!-- Dynamic content inserted here -->
        </div>
    </div>

    <script>
        // Configuration
        const BOOKING_ID = new URLSearchParams(window.location.search).get('booking_id');
        const API_BASE = window.location.origin;

        // Initialize payment flow
        async function initPayment() {
            // Step 1: Create payment intent
            const intent = await createPaymentIntent(BOOKING_ID);
            if (!intent) return;

            // Step 2: Show payment options
            showPaymentOptions(intent.payment_intent_id, intent.amount);
        }

        // Show UI for payment method selection
        function showPaymentOptions(paymentIntentId, amount) {
            const html = `
                <div class="alert alert-info">
                    <p><strong>Amount to Pay:</strong> ${amount} KES</p>
                </div>
                
                <form onsubmit="handlePaymentMethod(event, ${paymentIntentId}, ${amount})">
                    <div class="form-group">
                        <label for="phone">Your Phone Number *</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            placeholder="0712345678 or +254712345678"
                            required
                        />
                        <small>We'll send M-PESA prompt to this number</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        Pay with M-PESA
                    </button>
                </form>
            `;
            
            document.getElementById('payment-container').innerHTML = html;
        }

        // Handle payment initiation
        async function handlePaymentMethod(event, paymentIntentId, amount) {
            event.preventDefault();
            const phone = document.getElementById('phone').value;
            
            showLoading('Sending M-PESA prompt...');
            
            // Try STK first
            const stkResult = await initiateSTKPayment(paymentIntentId, phone);
            
            // If STK fails, show manual fallback
            if (!stkResult) {
                // Timeout for fallback after 30 seconds
                setTimeout(() => {
                    // Check if still pending
                    checkPaymentStatus(paymentIntentId);
                }, 30000);
            }
        }

        // Utility functions
        function showLoading(message) {
            document.getElementById('payment-container').innerHTML = `
                <div class="alert alert-info">
                    <p>${message}</p>
                    <p style="text-align: center; margin-top: 20px;">
                        <span style="font-size: 2em;">⏳</span>
                    </p>
                </div>
            `;
        }

        function showSuccess(message) {
            document.getElementById('payment-container').innerHTML = `
                <div class="alert alert-success">
                    <p>✓ ${message}</p>
                </div>
            `;
        }

        function showError(message) {
            document.getElementById('payment-container').innerHTML = `
                <div class="alert alert-error">
                    <p>✗ ${message}</p>
                </div>
            `;
        }

        function showWarning(message) {
            document.getElementById('payment-container').innerHTML = `
                <div class="alert alert-info">
                    <p>⚠ ${message}</p>
                </div>
            `;
        }

        function showInfo(message) {
            document.getElementById('payment-container').innerHTML = `
                <div class="alert alert-info">
                    <p>ℹ ${message}</p>
                </div>
            `;
        }

        function showMessage(message) {
            showInfo(message);
        }

        function redirectToBookingConfirmation() {
            setTimeout(() => {
                window.location.href = '/booking/confirmation?booking_id=' + BOOKING_ID;
            }, 2000);
        }

        // Start payment flow on page load
        initPayment();
    </script>
</body>
</html>
```

---

## Error Handling Examples

```javascript
const ERRORS = {
  'INVALID_RECEIPT': 'Receipt format is invalid. Use format like LIK123ABC456',
  'RECEIPT_EXISTS': 'This receipt has already been used',
  'AMOUNT_MISMATCH': 'The amount you entered does not match the booking amount',
  'PAYMENT_FAILED': 'Payment could not be processed. Please try again.',
  'STK_FAILED': 'Could not send M-PESA prompt. Try manual payment instead.',
  'NETWORK_ERROR': 'Network error. Please check your connection.',
};

function handleError(errorCode, defaultMessage) {
  const message = ERRORS[errorCode] || defaultMessage;
  showError(message);
}
```

---

## Summary

### User Journey

**Scenario 1: STK Success (Best Case)**
```
1. User enters phone number
2. System sends STK prompt
3. User enters M-PESA PIN
4. Payment auto-verified
5. Email sent immediately
6. Booking confirmed
```
Time: ~30 seconds

**Scenario 2: Manual Fallback (STK Failed)**
```
1. User enters phone number
2. STK prompt fails/timeout
3. System shows till number
4. User pays to till (*138#)
5. User gets receipt (LIK123ABC456)
6. User enters receipt number
7. System shows: "Under review"
8. Admin verifies within 24 hours
9. User gets confirmation email
10. Booking confirmed
```
Time: 24+ hours

