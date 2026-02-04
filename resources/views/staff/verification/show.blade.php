@extends('layouts.velzon.app')

@section('title', 'Verify Payment')
@section('page-title', 'Verify Payment')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Staff Portal</p>
                <h1>Verify Payment</h1>
                <p class="lede">Booking #{{ $booking->id }}</p>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Booking Details -->
            <div class="card">
                <div class="card-head">
                    <h3>Booking Information</h3>
                </div>
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Guest Name</p>
                        <p style="margin: 0; font-weight: 600;">{{ $booking->guest->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Property</p>
                        <p style="margin: 0; font-weight: 600;">{{ $booking->property->name ?? 'N/A' }}</p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Check-in</p>
                            <p style="margin: 0;">{{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Check-out</p>
                            <p style="margin: 0;">{{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Total Amount</p>
                        <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--brand-primary, #652482);">{{ number_format($booking->total_amount) }} KES</p>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card">
                <div class="card-head">
                    <h3>Payment Details</h3>
                </div>
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">M-PESA Receipt</p>
                        @if($booking->mpesa_receipt_number)
                            <code style="background: #FFF3E0; padding: 0.5rem 1rem; border-radius: 6px; display: inline-block; font-size: 1.125rem; font-weight: 700;">{{ $booking->mpesa_receipt_number }}</code>
                        @else
                            <p style="margin: 0; color: #C62828;">No receipt number provided</p>
                        @endif
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Payment Status</p>
                        <span class="pill" style="background: #FFF3E0; color: #E65100;">{{ $booking->payment_status }}</span>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Amount Paid</p>
                        <p style="margin: 0; font-weight: 600;">{{ number_format($booking->amount_paid) }} KES</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Submitted</p>
                        <p style="margin: 0;">{{ $booking->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Verification Form -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Verification</h3>
                <p class="muted">Confirm payment has been received</p>
            </div>
            <form action="{{ route('staff.verification.verify', $booking) }}" method="POST">
                @csrf
                <div style="background: #FFF9E6; border-left: 4px solid #E65100; padding: 1rem; margin-bottom: 1.5rem; border-radius: 4px;">
                    <p style="margin: 0; font-weight: 600; color: #E65100;">⚠️ Verification Checklist</p>
                    <ul style="margin: 0.5rem 0 0; padding-left: 1.5rem; color: #5a5661;">
                        <li>Confirm M-PESA receipt number is valid</li>
                        <li>Verify amount matches the booking total</li>
                        <li>Check payment is from the correct guest</li>
                        <li>Ensure payment has not been reversed</li>
                    </ul>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label class="label">Verification Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Add any relevant notes about this verification..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="pill" style="background: #2E7D32; color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-weight: 600;">✓ Confirm Payment</button>
                    <a href="{{ route('staff.verification.index') }}" class="pill light" style="display: inline-flex; align-items: center; text-decoration: none;">Cancel</a>
                </div>
            </form>
        </div>

        <div style="background: #E3F2FD; padding: 1rem; margin-top: 1.5rem; border-radius: 8px; border-left: 4px solid #1565C0;">
            <p style="margin: 0; color: #1565C0; font-size: 0.875rem;"><strong>Note:</strong> Staff can only confirm payments. Contact an admin if you need to reject or flag a payment for review.</p>
        </div>
    </div>
@endsection
