#!/bin/bash

# M-PESA Integration Testing Script
# Run this to test the complete payment flow

API_BASE="http://localhost:8001"
BOOKING_ID=1
PAYMENT_AMOUNT=5000
PHONE="+254712345678"

echo "=== M-PESA STK Push Integration Test ==="
echo ""

# Test 1: Get booking summary
echo "Step 1: Get booking summary..."
SUMMARY=$(curl -s "${API_BASE}/bookings/${BOOKING_ID}/summary" | jq -r '.data')
echo "Booking Reference: $(echo $SUMMARY | jq -r '.booking_ref')"
echo "Amount Due: $(echo $SUMMARY | jq -r '.amount_due')"
echo ""

# Test 2: Create payment intent
echo "Step 2: Creating payment intent..."
INTENT=$(curl -s -X POST "${API_BASE}/payment/intents" \
  -H "Content-Type: application/json" \
  -d "{\"booking_id\": ${BOOKING_ID}, \"amount\": ${PAYMENT_AMOUNT}}" \
  | jq -r '.data')

PAYMENT_INTENT_ID=$(echo $INTENT | jq -r '.payment_intent_id')
echo "Payment Intent ID: ${PAYMENT_INTENT_ID}"
echo "Status: $(echo $INTENT | jq -r '.status')"
echo ""

# Test 3: Get payment options
echo "Step 3: Getting payment options..."
OPTIONS=$(curl -s "${API_BASE}/payment/bookings/${BOOKING_ID}/options" | jq '.data.options')
echo "Deposit: $(echo $OPTIONS | jq -r '.deposit.amount')"
echo "Full Amount: $(echo $OPTIONS | jq -r '.full.amount')"
echo ""

# Test 4: Initiate STK Push
echo "Step 4: Initiating STK Push..."
STK=$(curl -s -X POST "${API_BASE}/payment/mpesa/stk" \
  -H "Content-Type: application/json" \
  -d "{\"payment_intent_id\": ${PAYMENT_INTENT_ID}, \"phone_e164\": \"${PHONE}\"}" \
  | jq -r '.data')

STK_REQUEST_ID=$(echo $STK | jq -r '.stk_request_id')
CHECKOUT_REQUEST_ID=$(echo $STK | jq -r '.checkout_request_id')
echo "STK Request ID: ${STK_REQUEST_ID}"
echo "Checkout Request ID: ${CHECKOUT_REQUEST_ID}"
echo "Status: $(echo $STK | jq -r '.status')"
echo ""

# Test 5: Poll status
echo "Step 5: Polling payment status..."
for i in {1..3}; do
  STATUS=$(curl -s "${API_BASE}/payment/mpesa/stk/${STK_REQUEST_ID}/status" | jq -r '.data')
  echo "Poll #${i}:"
  echo "  STK Status: $(echo $STATUS | jq -r '.stk_status')"
  echo "  Payment Status: $(echo $STATUS | jq -r '.payment_intent_status')"
  echo "  Booking Status: $(echo $STATUS | jq -r '.booking_status')"
  echo "  Has Callback: $(echo $STATUS | jq -r '.has_callback')"
  sleep 2
done
echo ""

# Test 6: Get payment history (before callback)
echo "Step 6: Getting payment history (before payment)..."
HISTORY=$(curl -s "${API_BASE}/payment/bookings/${BOOKING_ID}/history" | jq '.data')
echo "Amount Paid: $(echo $HISTORY | jq -r '.payment_summary.amount_paid')"
echo "Amount Due: $(echo $HISTORY | jq -r '.payment_summary.amount_due')"
echo "Transactions: $(echo $HISTORY | jq '.transactions | length')"
echo ""

echo "=== Test Complete ==="
echo ""
echo "Next Steps:"
echo "1. Wait for M-PESA STK prompt on phone: ${PHONE}"
echo "2. Enter M-PESA PIN"
echo "3. Payment will be processed automatically"
echo "4. Run payment history query to verify:"
echo "   curl ${API_BASE}/payment/bookings/${BOOKING_ID}/history | jq"
echo ""
