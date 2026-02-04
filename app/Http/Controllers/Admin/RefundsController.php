<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundsController extends Controller
{
    public function index(Request $request)
    {
        $query = Refund::with(['booking.guest'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $refunds = $query->paginate(25);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(Refund $refund)
    {
        $refund->load(['booking.guest', 'booking.property', 'requester', 'approver']);
        return view('admin.refunds.show', compact('refund'));
    }

    public function create(Request $request)
    {
        $booking = Booking::findOrFail($request->booking_id);
        return view('admin.refunds.create', compact('booking'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'reason' => 'required|in:CANCELLATION,OVERPAYMENT,SERVICE_ISSUE,OTHER',
            'notes' => 'nullable|string',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        // Validate refund amount doesn't exceed amount paid
        if ($validated['amount'] > $booking->amount_paid) {
            return back()->withErrors(['amount' => 'Refund amount cannot exceed amount paid']);
        }

        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'PENDING';

        $refund = Refund::create($validated);

        Log::info('Refund request created', [
            'refund_id' => $refund->id,
            'booking_id' => $booking->id,
            'amount' => $validated['amount'],
            'requested_by' => auth()->user()->name,
        ]);

        return redirect()->route('admin.refunds.show', $refund)
            ->with('success', 'Refund request created successfully');
    }

    public function approve(Request $request, Refund $refund)
    {
        if ($refund->status !== 'PENDING') {
            return back()->with('error', 'Only pending refunds can be approved');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:MPESA,BANK_TRANSFER,CASH',
            'admin_notes' => 'nullable|string',
        ]);

        DB::transaction(function() use ($refund, $validated) {
            $refund->update([
                'status' => 'APPROVED',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'payment_method' => $validated['payment_method'],
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Update booking amounts
            $booking = $refund->booking;
            $booking->update([
                'amount_paid' => max(0, $booking->amount_paid - $refund->amount),
                'amount_due' => $booking->amount_due + $refund->amount,
            ]);

            Log::info('Refund approved', [
                'refund_id' => $refund->id,
                'booking_id' => $booking->id,
                'amount' => $refund->amount,
                'approved_by' => auth()->user()->name,
            ]);
        });

        return back()->with('success', 'Refund approved successfully');
    }

    public function reject(Request $request, Refund $refund)
    {
        if ($refund->status !== 'PENDING') {
            return back()->with('error', 'Only pending refunds can be rejected');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $refund->update([
            'status' => 'REJECTED',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        Log::info('Refund rejected', [
            'refund_id' => $refund->id,
            'booking_id' => $refund->booking_id,
            'rejected_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Refund rejected');
    }

    public function markProcessed(Request $request, Refund $refund)
    {
        if ($refund->status !== 'APPROVED') {
            return back()->with('error', 'Only approved refunds can be marked as processed');
        }

        $validated = $request->validate([
            'mpesa_ref' => 'nullable|string',
            'admin_notes' => 'nullable|string',
        ]);

        $refund->update([
            'status' => 'PROCESSED',
            'processed_at' => now(),
            'mpesa_ref' => $validated['mpesa_ref'] ?? $refund->mpesa_ref,
            'admin_notes' => $validated['admin_notes'] ?? $refund->admin_notes,
        ]);

        Log::info('Refund marked as processed', [
            'refund_id' => $refund->id,
            'processed_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Refund marked as processed');
    }
}
