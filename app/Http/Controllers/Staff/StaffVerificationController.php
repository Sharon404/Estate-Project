<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\MpesaManualSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StaffVerificationController extends Controller
{
    /**
     * Staff can view pending verifications
     */
    public function index()
    {
        $submissions = MpesaManualSubmission::with(['booking.guest', 'booking.property'])
            ->where('status', 'PENDING')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Stats
        $stats = [
            'pending' => MpesaManualSubmission::where('status', 'PENDING')->count(),
            'verified_today' => MpesaManualSubmission::where('status', 'VERIFIED')->count(),
            'your_verifications' => MpesaManualSubmission::where('status', 'VERIFIED')->count(),
        ];

        return view('staff.verification.index', [
            'submissions' => $submissions,
            'bookings' => $submissions, // Alias for backward compatibility
            'stats' => $stats,
        ]);
    }

    /**
     * Staff can view submission details
     */
    public function show(MpesaManualSubmission $submission)
    {
        $submission->load(['booking.guest', 'verifiedBy']);

        return view('staff.verification.show', [
            'submission' => $submission,
        ]);
    }

    /**
     * Staff can verify payments (limited to CONFIRMED only, no REJECT)
     */
    public function verify(Request $request, MpesaManualSubmission $submission)
    {
        // Only allow if still pending
        if ($submission->status !== 'PENDING') {
            return back()->with('error', 'This submission has already been processed.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        // Staff can only confirm, not reject
        $submission->update([
            'status' => 'VERIFIED',
            'raw_notes' => $validated['notes'] ?? null,
        ]);

        // Create booking transaction
        $booking = $submission->booking;
        
        \App\Models\BookingTransaction::create([
            'booking_id' => $booking->id,
            'external_ref' => $submission->mpesa_code,
            'source' => 'MPESA_MANUAL',
            'amount' => $submission->amount,
            'status' => 'COMPLETED',
            'posted_at' => now(),
        ]);

        // Update booking
        $booking->update([
            'status' => 'PAID',
            'amount_paid' => $booking->amount_paid + $submission->amount,
            'amount_due' => max(0, $booking->amount_due - $submission->amount),
        ]);

        Log::info('M-Pesa payment verified by staff', [
            'submission_id' => $submission->id,
            'booking_id' => $booking->id,
            'verified_by' => Auth::user()->name,
        ]);

        return back()->with('success', 'Payment verified successfully');
    }
}
