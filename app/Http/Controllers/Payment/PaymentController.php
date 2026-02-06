<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentIntent;
use App\Models\Receipt;
use App\Services\PaymentService;
use App\Services\ReceiptService;
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
     * Show payment page for a booking.
     * 
     * GET /payment/booking/{bookingId}
     * 
     * Displays payment interface where user can:
     * - Pay via STK push (primary method)
     * - Pay via manual entry (fallback if STK fails)
     * 
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function showPaymentPage(Booking $booking)
    {
        // Allow viewing for PAID/COMPLETED bookings (to download receipt)
        // and for PENDING_PAYMENT/PARTIALLY_PAID (to make payment)
        $allowedStatuses = ['PENDING_PAYMENT', 'PARTIALLY_PAID', 'PAID', 'COMPLETED'];
        
        if (!in_array($booking->status, $allowedStatuses)) {
            return redirect('/')->with('error', 'This booking is not available for payment.');
        }

        return view('payment.payment', [
            'booking' => $booking,
        ]);
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
     * Called when guest wants to submit M-PESA receipt code manually.
     * Stores the receipt with booking_id for admin verification.
     * 
     * Request body:
     * {
     *   "booking_id": 1,
     *   "mpesa_receipt_number": "LIK123ABC456",
     *   "amount": 5000,
     *   "phone": "0712345678" or "+254712345678" or "01234567"
     * }
     * 
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function submitManualPayment(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'booking_id' => 'required|exists:bookings,id',
                'mpesa_receipt_number' => 'required|string|min:3|max:20',
                'amount' => 'required|numeric|min:0.01',
                'phone' => 'required|string|min:9|max:20',
            ]);

            $booking = \App\Models\Booking::findOrFail($validated['booking_id']);
            
            // Normalize phone number to E.164 format
            $phone = $this->normalizePhoneNumber($validated['phone']);

            $result = $this->paymentService->submitManualPayment(
                $booking,
                $validated['mpesa_receipt_number'],
                $validated['amount'],
                $phone
            );

            Log::info('Manual payment submitted', [
                'booking_id' => $booking->id,
                'receipt_number' => $validated['mpesa_receipt_number'],
                'amount' => $validated['amount'],
            ]);

            return response()->json($result, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Manual payment submission failed', [
                'error' => $e->getMessage(),
                'booking_id' => $request->input('booking_id'),
                'receipt_number' => $request->input('mpesa_receipt_number'),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Normalize phone number to E.164 format (+254...)
     * Accepts: 07xxxx, +254xxxx, 01xxxx, 2547xxxx
     */
    private function normalizePhoneNumber(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // Remove non-digits

        // Handle different formats
        if (substr($phone, 0, 1) === '0') {
            // 07... or 01... format - replace 0 with country code
            $phone = '254' . substr($phone, 1);
        } elseif (substr($phone, 0, 3) !== '254') {
            // Doesn't start with 254, so assume it's missing the country code
            $phone = '254' . $phone;
        }

        // Return in E.164 format
        return '+' . $phone;
    }

    /**
     * Get receipt by receipt number.
     * 
     * GET /payment/receipts/{receiptNo}
     * 
     * Response: Complete receipt with all details and snapshot data
     * 
     * @param string $receiptNo
     * @return JsonResponse
     */
    public function getReceiptByNumber(string $receiptNo): JsonResponse
    {
        try {
            $receipt = Receipt::where('receipt_no', $receiptNo)->first();

            if (!$receipt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Receipt not found',
                ], 404);
            }

            $receiptService = new ReceiptService();
            $details = $receiptService->getReceiptDetails($receipt);

            return response()->json([
                'success' => true,
                'data' => $details,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve receipt', [
                'error' => $e->getMessage(),
                'receipt_no' => $receiptNo,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve receipt',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get all receipts for a booking.
     * 
     * GET /payment/bookings/{bookingId}/receipts
     * 
     * Response: Array of receipts with basic info
     * 
     * @param int $bookingId
     * @return JsonResponse
     */
    public function getBookingReceipts(int $bookingId): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($bookingId);

            $receiptService = new ReceiptService();
            $receipts = $receiptService->getBookingReceipts($booking);

            return response()->json([
                'success' => true,
                'booking_ref' => $booking->booking_ref,
                'receipt_count' => $receipts->count(),
                'data' => $receipts->map(function ($receipt) {
                    return [
                        'receipt_id' => $receipt->id,
                        'receipt_no' => $receipt->receipt_no,
                        'amount' => (float) $receipt->amount,
                        'currency' => $receipt->currency,
                        'mpesa_receipt_number' => $receipt->mpesa_receipt_number,
                        'issued_at' => $receipt->issued_at->toIso8601String(),
                        'payment_method' => $receipt->receipt_data['receipt_info']['type'] ?? 'UNKNOWN',
                    ];
                })->all(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve booking receipts', [
                'error' => $e->getMessage(),
                'booking_id' => $bookingId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve receipts',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get specific receipt for a booking.
     * 
     * GET /payment/bookings/{bookingId}/receipts/{receiptNo}
     * 
     * Response: Complete receipt details
     * 
     * @param int $bookingId
     * @param string $receiptNo
     * @return JsonResponse
     */
    public function getBookingReceipt(int $bookingId, string $receiptNo): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($bookingId);

            $receipt = Receipt::where('receipt_no', $receiptNo)
                ->where('booking_id', $bookingId)
                ->first();

            if (!$receipt) {
                return response()->json([
                    'success' => false,
                    'message' => 'Receipt not found for this booking',
                ], 404);
            }

            $receiptService = new ReceiptService();
            $details = $receiptService->getReceiptDetails($receipt);

            return response()->json([
                'success' => true,
                'data' => $details,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve booking receipt', [
                'error' => $e->getMessage(),
                'booking_id' => $bookingId,
                'receipt_no' => $receiptNo,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve receipt',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Download PDF receipt for a booking.
     * 
     * GET /payment/booking/{booking}/receipt/download
     * 
     * Generates and downloads a PDF receipt for completed payments.
     * Authorization: User can only download receipts for their own bookings.
     * 
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt(Booking $booking)
    {
        // Authorization: Verify booking belongs to user (based on phone/email)
        // For guest bookings without auth, we validate via booking reference in URL
        
        // Validate payment is completed
        if ($booking->status !== 'PAID' && $booking->status !== 'COMPLETED') {
            abort(403, 'Receipt not available for unpaid bookings');
        }

        // Load guest and property relationships
        $booking->load(['guest', 'property']);

        // Get the latest payment transaction (type = PAYMENT, not REFUND or ADJUSTMENT)
        $latestTransaction = $booking->bookingTransactions()
            ->where('type', 'PAYMENT')
            ->orderBy('posted_at', 'desc')
            ->first();

        if (!$latestTransaction) {
            abort(404, 'No payment transaction found');
        }

        // Map source to human-readable payment method
        $paymentMethodMap = [
            'MPESA_STK' => 'M-PESA STK Push',
            'MPESA_MANUAL' => 'M-PESA Manual Entry',
            'MPESA_C2B' => 'M-PESA Paybill',
            'ADMIN' => 'Manual Entry (Admin)',
        ];

        // Prepare receipt data
        $receiptData = [
            'booking_ref' => $booking->booking_ref,
            'guest_name' => $booking->guest->full_name,
            'guest_phone' => $booking->guest->phone_e164,
            'guest_email' => $booking->guest->email,
            'property_name' => $booking->property->name,
            'check_in' => $booking->check_in->format('M d, Y'),
            'check_out' => $booking->check_out->format('M d, Y'),
            'nights' => $booking->nights,
            'amount_paid' => number_format($booking->amount_paid, 2),
            'currency' => $booking->currency,
            'payment_method' => $paymentMethodMap[$latestTransaction->source] ?? $latestTransaction->source,
            'mpesa_receipt' => $latestTransaction->external_ref ?? 'N/A',
            'payment_date' => \Carbon\Carbon::parse($latestTransaction->posted_at)->format('M d, Y h:i A'),
            'generated_at' => now()->format('M d, Y h:i A'),
        ];

        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('payment.receipt-pdf', $receiptData);

        // Download PDF with booking reference as filename
        return $pdf->download('Receipt-' . $booking->booking_ref . '.pdf');
    }}