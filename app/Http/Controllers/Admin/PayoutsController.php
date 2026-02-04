<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutsController extends Controller
{
    public function index(Request $request)
    {
        $query = Payout::with(['property', 'booking'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payee_type')) {
            $query->where('payee_type', $request->payee_type);
        }

        $payouts = $query->paginate(25);

        return view('admin.payouts.index', compact('payouts'));
    }

    public function show(Payout $payout)
    {
        $payout->load(['property', 'booking', 'approver']);
        return view('admin.payouts.show', compact('payout'));
    }

    public function create()
    {
        $properties = Property::all();
        return view('admin.payouts.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'payee_type' => 'required|in:OWNER,STAFF,COMMISSION',
            'payee_name' => 'required|string|max:255',
            'payee_phone' => 'nullable|string|max:20',
            'payee_account' => 'nullable|string|max:255',
            'gross_amount' => 'required|numeric|min:0',
            'commission_amount' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['commission_amount'] = $validated['commission_amount'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['net_amount'] = $validated['gross_amount'] - $validated['commission_amount'] - $validated['deductions'];
        $validated['status'] = 'PENDING';

        $payout = Payout::create($validated);

        Log::info('Payout created', [
            'payout_id' => $payout->id,
            'payout_ref' => $payout->payout_ref,
            'net_amount' => $payout->net_amount,
            'created_by' => auth()->user()->name,
        ]);

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout created successfully');
    }

    public function approve(Request $request, Payout $payout)
    {
        if ($payout->status !== 'PENDING') {
            return back()->with('error', 'Only pending payouts can be approved');
        }

        $payout->update([
            'status' => 'APPROVED',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Log::info('Payout approved', [
            'payout_id' => $payout->id,
            'approved_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Payout approved successfully');
    }

    public function markCompleted(Request $request, Payout $payout)
    {
        if (!in_array($payout->status, ['APPROVED', 'PROCESSING'])) {
            return back()->with('error', 'Only approved/processing payouts can be marked as completed');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:MPESA,BANK_TRANSFER,CASH',
            'mpesa_ref' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $payout->update([
            'status' => 'COMPLETED',
            'completed_at' => now(),
            'payment_method' => $validated['payment_method'],
            'mpesa_ref' => $validated['mpesa_ref'],
            'notes' => $validated['notes'] ?? $payout->notes,
        ]);

        Log::info('Payout completed', [
            'payout_id' => $payout->id,
            'completed_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Payout marked as completed');
    }

    public function markDisputed(Request $request, Payout $payout)
    {
        $validated = $request->validate([
            'dispute_reason' => 'required|string',
        ]);

        $payout->update([
            'status' => 'DISPUTED',
            'dispute_reason' => $validated['dispute_reason'],
        ]);

        Log::warning('Payout disputed', [
            'payout_id' => $payout->id,
            'disputed_by' => auth()->user()->name,
        ]);

        return back()->with('success', 'Payout marked as disputed');
    }
}
