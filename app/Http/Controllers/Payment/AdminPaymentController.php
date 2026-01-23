<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\MpesaManualSubmission;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * AdminPaymentController
 * 
 * Handles admin payment verification and oversight:
 * 1. Get pending manual submissions
 * 2. Verify manual M-PESA payments
 * 3. Reject invalid submissions
 * 4. View payment status
 * 
 * Requires admin authentication
 */
class AdminPaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        // Middleware can be added to routes to ensure admin access
    }

    /**
     * Get pending manual M-PESA submissions.
     * 
     * GET /admin/payment/manual-submissions/pending
     * 
     * Returns all submissions awaiting admin verification.
     * 
     * @return JsonResponse
     */
    public function getPendingSubmissions(): JsonResponse
    {
        try {
            $result = $this->paymentService->getPendingManualSubmissions();

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get pending submissions', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pending submissions',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get single manual submission details.
     * 
     * GET /admin/payment/manual-submissions/{submissionId}
     * 
     * @param MpesaManualSubmission $submission
     * @return JsonResponse
     */
    public function getSubmissionDetails(MpesaManualSubmission $submission): JsonResponse
    {
        $paymentIntent = $submission->paymentIntent;
        $booking = $paymentIntent->booking;
        $guest = $booking->guest;

        return response()->json([
            'success' => true,
            'data' => [
                'submission_id' => $submission->id,
                'receipt_number' => $submission->mpesa_receipt_number,
                'amount' => (float) $submission->amount,
                'phone_e164' => $submission->phone_e164,
                'status' => $submission->status,
                'submitted_at' => $submission->submitted_at,
                'reviewed_at' => $submission->reviewed_at,
                'notes' => $submission->raw_notes,
                'payment_intent' => [
                    'id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                    'amount' => (float) $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                ],
                'booking' => [
                    'id' => $booking->id,
                    'booking_ref' => $booking->booking_ref,
                    'status' => $booking->status,
                    'total_amount' => (float) $booking->total_amount,
                    'amount_paid' => (float) $booking->amount_paid,
                    'amount_due' => (float) $booking->amount_due,
                    'check_in' => $booking->check_in,
                    'check_out' => $booking->check_out,
                    'nights' => $booking->nights,
                    'property_name' => $booking->property->name ?? null,
                ],
                'guest' => [
                    'id' => $guest->id,
                    'name' => $guest->name,
                    'email' => $guest->email,
                    'phone' => $guest->phone,
                ],
            ],
        ]);
    }

    /**
     * Verify manual M-PESA payment and process it.
     * 
     * POST /admin/payment/manual-submissions/{submissionId}/verify
     * 
     * Creates ledger entry, updates payment intent status, and updates booking amounts.
     * 
     * Request body:
     * {
     *   "verified_notes": "Receipt verified against M-PESA statement"  // optional
     * }
     * 
     * @param MpesaManualSubmission $submission
     * @param Request $request
     * @return JsonResponse
     */
    public function verifySubmission(MpesaManualSubmission $submission, Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'verified_notes' => 'nullable|string|max:500',
            ]);

            $result = $this->paymentService->verifyManualPayment($submission);

            Log::info('Manual payment verified', [
                'submission_id' => $submission->id,
                'receipt_number' => $submission->mpesa_receipt_number,
                'amount' => $submission->amount,
                'verified_notes' => $validated['verified_notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Manual payment verified successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Manual payment verification failed', [
                'error' => $e->getMessage(),
                'submission_id' => $submission->id,
                'receipt_number' => $submission->mpesa_receipt_number,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to verify manual payment',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reject manual M-PESA submission.
     * 
     * POST /admin/payment/manual-submissions/{submissionId}/reject
     * 
     * Rejects the submission without creating any ledger entry.
     * Guest can resubmit with correct receipt or try STK again.
     * 
     * Request body:
     * {
     *   "reason": "Receipt number does not match M-PESA statement"
     * }
     * 
     * @param MpesaManualSubmission $submission
     * @param Request $request
     * @return JsonResponse
     */
    public function rejectSubmission(MpesaManualSubmission $submission, Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'reason' => 'required|string|max:500',
            ]);

            $result = $this->paymentService->rejectManualPayment(
                $submission,
                $validated['reason']
            );

            Log::info('Manual payment rejected', [
                'submission_id' => $submission->id,
                'receipt_number' => $submission->mpesa_receipt_number,
                'reason' => $validated['reason'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Manual submission rejected',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Manual payment rejection failed', [
                'error' => $e->getMessage(),
                'submission_id' => $submission->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject manual submission',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get payment verification statistics.
     * 
     * GET /admin/payment/statistics
     * 
     * Returns stats on manual submissions and overall payment status.
     * 
     * @return JsonResponse
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $pending = MpesaManualSubmission::where('status', 'SUBMITTED')->count();
            $verified = MpesaManualSubmission::where('status', 'VERIFIED')->count();
            $rejected = MpesaManualSubmission::where('status', 'REJECTED')->count();

            $pendingAmount = MpesaManualSubmission::where('status', 'SUBMITTED')->sum('amount');
            $verifiedAmount = MpesaManualSubmission::where('status', 'VERIFIED')->sum('amount');

            return response()->json([
                'success' => true,
                'data' => [
                    'submissions' => [
                        'pending_count' => $pending,
                        'pending_amount' => (float) $pendingAmount,
                        'verified_count' => $verified,
                        'verified_amount' => (float) $verifiedAmount,
                        'rejected_count' => $rejected,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to get payment statistics', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
