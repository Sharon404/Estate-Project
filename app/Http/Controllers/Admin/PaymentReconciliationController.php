<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Receipt;
use App\Models\PaymentIntent;
use App\Models\MpesaManualSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentReconciliationController extends Controller
{
    public function index()
    {
        // Bookings with payment mismatches
        $mismatches = Booking::where(function($query) {
            $query->where('status', 'PAID')
                  ->whereColumn('amount_paid', '<', 'total_amount');
        })->orWhere(function($query) {
            $query->whereIn('status', ['PENDING_PAYMENT', 'PARTIALLY_PAID'])
                  ->whereColumn('amount_paid', '>=', 'minimum_deposit');
        })->with(['guest', 'property'])->get();

        // Pending manual submissions
        $pendingSubmissions = MpesaManualSubmission::where('status', 'PENDING')
            ->with(['booking.guest'])
            ->latest()
            ->limit(10)
            ->get();

        // Failed payment intents
        $failedIntents = PaymentIntent::where('status', 'FAILED')
            ->with(['booking.guest'])
            ->latest()
            ->limit(10)
            ->get();

        // Duplicate receipts (same M-PESA code)
        $duplicateReceipts = Receipt::select('mpesa_receipt_number', DB::raw('COUNT(*) as count'))
            ->whereNotNull('mpesa_receipt_number')
            ->groupBy('mpesa_receipt_number')
            ->having('count', '>', 1)
            ->get();

        // Orphaned payments (receipts without bookings)
        $orphanedPayments = Receipt::whereNull('booking_id')
            ->orWhereDoesntHave('booking')
            ->orderByDesc('issued_at')
            ->limit(10)
            ->get();

        // Summary stats
        $stats = [
            'mismatches_count' => $mismatches->count(),
            'pending_verifications' => $pendingSubmissions->count(),
            'failed_intents' => $failedIntents->count(),
            'duplicate_receipts' => $duplicateReceipts->count(),
            'orphaned_payments' => $orphanedPayments->count(),
        ];

        return view('admin.reconciliation.index', compact(
            'mismatches',
            'pendingSubmissions',
            'failedIntents',
            'duplicateReceipts',
            'orphanedPayments',
            'stats'
        ));
    }

    public function resolveMismatch(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'action' => 'required|in:adjust_paid,adjust_total,mark_correct',
            'notes' => 'required|string',
        ]);

        DB::transaction(function() use ($booking, $validated) {
            if ($validated['action'] === 'adjust_paid') {
                // Adjust amount_paid to match receipts
                $totalReceipts = $booking->receipts()->sum('amount');
                $booking->update([
                    'amount_paid' => $totalReceipts,
                    'amount_due' => max(0, $booking->total_amount - $totalReceipts),
                ]);
            } elseif ($validated['action'] === 'adjust_total') {
                // Adjust total_amount to match payments
                $booking->update([
                    'total_amount' => $booking->amount_paid,
                    'amount_due' => 0,
                ]);
            }

            // Log resolution
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'payment_mismatch_resolved',
                'resource_type' => 'Booking',
                'resource_id' => $booking->id,
                'metadata' => [
                    'action' => $validated['action'],
                    'notes' => $validated['notes'],
                ],
            ]);
        });

        return back()->with('success', 'Payment mismatch resolved');
    }
}
