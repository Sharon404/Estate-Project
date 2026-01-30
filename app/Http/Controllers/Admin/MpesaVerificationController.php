<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\MpesaManualSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MpesaVerificationController extends Controller
{
    /**
     * Display list of pending M-Pesa code submissions for verification
     */
    public function index()
    {
        $submissions = MpesaManualSubmission::with(['booking.guest'])
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.mpesa-verification.index', [
            'submissions' => $submissions,
        ]);
    }

    /**
     * Show details of a specific submission for verification
     */
    public function show(MpesaManualSubmission $submission)
    {
        $submission->load(['booking.guest', 'verifiedBy']);

        return view('admin.mpesa-verification.show', [
            'submission' => $submission,
        ]);
    }

    /**
     * Verify a payment and update booking status
     * 
     * POST /admin/mpesa-verification/{submission}/verify
     */
    public function verify(Request $request, MpesaManualSubmission $submission)
    {
        // Only allow if still pending
        if ($submission->status !== 'PENDING') {
            return back()->with('error', 'This submission has already been processed.');
        }

        $validated = $request->validate([
            'payment_status' => 'required|in:CONFIRMED,NOT_FOUND,MISMATCH',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Update submission with verification
            $submission->update([
                'status' => $validated['payment_status'] === 'CONFIRMED' ? 'VERIFIED' : 'REJECTED',
                'payment_status' => $validated['payment_status'],
                'admin_notes' => $validated['notes'],
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            // If payment is confirmed, mark booking as PAID
            if ($validated['payment_status'] === 'CONFIRMED') {
                $booking = $submission->booking;
                
                // Create booking transaction record
                \App\Models\BookingTransaction::create([
                    'booking_id' => $booking->id,
                    'external_ref' => $submission->mpesa_code,
                    'source' => 'MPESA_MANUAL',
                    'amount' => $submission->amount,
                    'status' => 'COMPLETED',
                    'posted_at' => now(),
                ]);

                // Update booking status
                $booking->update([
                    'status' => 'PAID',
                    'amount_paid' => $booking->amount_paid + $submission->amount,
                    'amount_due' => max(0, $booking->amount_due - $submission->amount),
                ]);

                Log::info('M-Pesa payment verified and booking marked as PAID', [
                    'submission_id' => $submission->id,
                    'booking_id' => $booking->id,
                    'mpesa_code' => $submission->mpesa_code,
                    'verified_by' => Auth::user()->name,
                ]);

                return back()->with('success', 'Payment verified! Booking has been marked as PAID.');
            } else {
                Log::warning('M-Pesa payment verification failed or payment not found', [
                    'submission_id' => $submission->id,
                    'booking_id' => $submission->booking_id,
                    'mpesa_code' => $submission->mpesa_code,
                    'payment_status' => $validated['payment_status'],
                    'verified_by' => Auth::user()->name,
                ]);

                return back()->with('warning', "Payment status: {$validated['payment_status']}. Booking remains unpaid.");
            }
        } catch (\Exception $e) {
            Log::error('Error verifying M-Pesa payment', [
                'error' => $e->getMessage(),
                'submission_id' => $submission->id,
            ]);

            return back()->with('error', 'Error verifying payment: ' . $e->getMessage());
        }
    }

    /**
     * Reject a submission
     */
    public function reject(Request $request, MpesaManualSubmission $submission)
    {
        if ($submission->status !== 'PENDING') {
            return back()->with('error', 'This submission has already been processed.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $submission->update([
            'status' => 'REJECTED',
            'payment_status' => 'NOT_FOUND',
            'admin_notes' => "Rejected: {$validated['reason']}",
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        Log::info('M-Pesa submission rejected', [
            'submission_id' => $submission->id,
            'booking_id' => $submission->booking_id,
            'reason' => $validated['reason'],
            'rejected_by' => Auth::user()->name,
        ]);

        return back()->with('info', 'Submission rejected. Guest will need to submit a valid M-Pesa code.');
    }

    /**
     * Check payment status against M-Pesa code using Daraja API
     */
    public function checkPaymentStatus(MpesaManualSubmission $submission)
    {
        try {
            // TODO: Implement actual M-Pesa Daraja API call to verify the code
            // This is a placeholder showing the structure
            
            $paymentStatus = $this->verifyWithDarajaAPI($submission->mpesa_code, $submission->amount);

            // Update submission with the check result
            $submission->update([
                'payment_check_response' => json_encode($paymentStatus),
                'payment_status' => $paymentStatus['status'] ?? 'NOT_CHECKED',
            ]);

            return response()->json([
                'success' => true,
                'payment_status' => $paymentStatus,
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking M-Pesa payment status', [
                'error' => $e->getMessage(),
                'submission_id' => $submission->id,
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify payment with M-Pesa Daraja API
     * This is a placeholder - implement with your actual API credentials
     */
    private function verifyWithDarajaAPI(string $mpesaCode, float $amount): array
    {
        // TODO: Replace with actual Daraja API implementation
        return [
            'status' => 'NOT_CHECKED',
            'message' => 'Daraja API integration not yet configured',
            'code' => $mpesaCode,
            'amount' => $amount,
        ];
    }
}
