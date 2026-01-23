<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentIntent;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * PaymentController
 * 
 * Handles payment flows:
 * 1. Create payment intents
 * 2. Get payment status
 * 3. Retrieve payment options
 * 4. Payment history
 */
class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Create a payment intent for a booking.
     * 
     * POST /payment/intents
     * 
     * Request body:
     * {
     *   "booking_id": 1,
     *   "amount": 5000  // optional, defaults to minimum_deposit
     * }
     * 
     * Response: PaymentIntent with status INITIATED
     * Next step: Send to /payment/mpesa/stk to initiate STK Push
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function createIntent(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'amount' => 'nullable|numeric|min:0.01',
            ]);

            $booking = Booking::findOrFail($validated['booking_id']);

            $paymentIntent = $this->paymentService->createPaymentIntent(
                $booking,
                $validated['amount'] ?? null
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment intent created successfully',
                'data' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'booking_id' => $paymentIntent->booking_id,
                    'amount' => (float) $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'status' => $paymentIntent->status,
                    'created_at' => $paymentIntent->created_at,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Payment intent creation failed', [
                'error' => $e->getMessage(),
                'booking_id' => $request->input('booking_id'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get payment intent details.
     * 
     * GET /payment/intents/{paymentIntentId}
     * 
     * @param PaymentIntent $paymentIntent
     * @return JsonResponse
     */
    public function getIntent(PaymentIntent $paymentIntent): JsonResponse
    {
        $booking = $paymentIntent->booking;
        $status = $this->paymentService->getPaymentStatus($paymentIntent);

        return response()->json([
            'success' => true,
            'data' => [
                'payment_intent_id' => $paymentIntent->id,
                'booking_id' => $booking->id,
                'booking_ref' => $booking->booking_ref,
                'amount' => (float) $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'status' => $paymentIntent->status,
                'payment_status' => $status['payment_status'],
                'booking_status' => $status['booking_status'],
                'amount_paid' => $status['amount_paid'],
                'amount_due' => $status['amount_due'],
                'stk_requests' => $paymentIntent->mpesaStkRequests()
                    ->latest()
                    ->get()
                    ->map(fn ($stk) => [
                        'id' => $stk->id,
                        'status' => $stk->status,
                        'phone_e164' => $stk->phone_e164,
                        'checkout_request_id' => $stk->checkout_request_id,
                        'created_at' => $stk->created_at,
                    ]),
            ],
        ]);
    }

    /**
     * Get payment options for a booking.
     * 
     * GET /payment/bookings/{bookingId}/options
     * 
     * Returns:
     * - Deposit amount
     * - Full payment amount
     * 
     * @param Booking $booking
     * @return JsonResponse
     */
    public function getPaymentOptions(Booking $booking): JsonResponse
    {
        try {
            $options = $this->paymentService->getPaymentOptions($booking);

            return response()->json([
                'success' => true,
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'total_amount' => (float) $booking->total_amount,
                    'amount_paid' => (float) $booking->amount_paid,
                    'amount_due' => (float) $booking->amount_due,
                    'currency' => $booking->currency,
                    'options' => $options,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get payment options', [
                'error' => $e->getMessage(),
                'booking_id' => $booking->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to get payment options',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Submit manual M-PESA payment.
     * 
     * POST /payment/manual-entry
     * 
     * Called when STK fails/times out and guest wants to enter receipt manually.
     * Creates submission in UNDER_REVIEW status awaiting admin verification.
     * 
     * Request body:
     * {
     *   "payment_intent_id": 1,
     *   "mpesa_receipt_number": "LIK123ABC456",
     *   "amount": 5000,
     *   "phone_e164": "+254712345678",  // optional
     *   "notes": "STK timed out, sending manual receipt"  // optional
     * }
     * 
     * @param \App\Http\Requests\SubmitManualMpesaRequest $request
     * @return JsonResponse
     */
    public function submitManualPayment(\App\Http\Requests\SubmitManualMpesaRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $paymentIntent = PaymentIntent::findOrFail($validated['payment_intent_id']);

            $result = $this->paymentService->submitManualPayment(
                $paymentIntent,
                $validated['mpesa_receipt_number'],
                $validated['amount'],
                $validated['phone_e164'] ?? null,
                $validated['notes'] ?? null
            );

            Log::info('Manual payment submitted', [
                'payment_intent_id' => $paymentIntent->id,
                'receipt_number' => $validated['mpesa_receipt_number'],
                'amount' => $validated['amount'],
            ]);

            return response()->json($result, 201);
        } catch (\Exception $e) {
            Log::error('Manual payment submission failed', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $request->input('payment_intent_id'),
                'receipt_number' => $request->input('mpesa_receipt_number'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit manual payment',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
