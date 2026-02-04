@extends('layouts.velzon.app')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Staff Portal</p>
                <h1>Booking Details</h1>
                <p class="lede">Booking #{{ $booking->id }}</p>
            </div>
            <span class="pill light" style="font-size: 0.875rem;">Read-Only Access</span>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Booking Information -->
            <div>
                <div class="card">
                    <div class="card-head">
                        <h3>Booking Information</h3>
                    </div>
                    <div style="display: grid; gap: 1.5rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.5rem; color: #5a5661; font-size: 0.875rem;">Property</p>
                            <p style="margin: 0; font-size: 1.25rem; font-weight: 700;">{{ $booking->property->name ?? 'N/A' }}</p>
                            <p style="margin: 0.25rem 0 0; color: #5a5661;">{{ $booking->property->location ?? '' }}</p>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Check-in</p>
                                <p style="margin: 0; font-weight: 600;">{{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Check-out</p>
                                <p style="margin: 0; font-weight: 600;">{{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Nights</p>
                                <p style="margin: 0; font-weight: 600;">
                                    {{ $booking->check_in_date && $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date)) : 'N/A' }} nights
                                </p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Number of Guests</p>
                                <p style="margin: 0; font-weight: 600;">{{ $booking->num_guests ?? 'N/A' }} guests</p>
                            </div>
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Booking Date</p>
                                <p style="margin: 0;">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>

                        @if($booking->special_requests)
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Special Requests</p>
                                <p style="margin: 0; line-height: 1.6; background: #F5F5F5; padding: 1rem; border-radius: 6px;">{{ $booking->special_requests }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Guest Information -->
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Guest Information</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Full Name</p>
                            <p style="margin: 0; font-weight: 600; font-size: 1.125rem;">{{ $booking->guest->name ?? 'N/A' }}</p>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Email</p>
                                <p style="margin: 0;">{{ $booking->guest->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Phone</p>
                                <p style="margin: 0;">{{ $booking->guest->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Status & Payment -->
                <div class="card">
                    <div class="card-head">
                        <h3>Status & Payment</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.5rem;">Booking Status</p>
                            @if($booking->status == 'CONFIRMED')
                                <span class="pill" style="background: #E8F5E9; color: #2E7D32; padding: 0.75rem 1rem;">✓ Confirmed</span>
                            @elseif($booking->status == 'PENDING')
                                <span class="pill" style="background: #FFF3E0; color: #E65100; padding: 0.75rem 1rem;">⏳ Pending</span>
                            @elseif($booking->status == 'CHECKED_IN')
                                <span class="pill" style="background: #E3F2FD; color: #1565C0; padding: 0.75rem 1rem;">✓ Checked In</span>
                            @elseif($booking->status == 'COMPLETED')
                                <span class="pill light" style="padding: 0.75rem 1rem;">✓ Completed</span>
                            @else
                                <span class="pill" style="background: #FFEBEE; color: #C62828; padding: 0.75rem 1rem;">✗ Cancelled</span>
                            @endif
                        </div>

                        <div>
                            <p class="label" style="margin: 0 0 0.5rem;">Payment Status</p>
                            @if($booking->payment_status == 'CONFIRMED')
                                <span class="pill" style="background: #E8F5E9; color: #2E7D32; padding: 0.75rem 1rem;">✓ Paid</span>
                            @elseif($booking->payment_status == 'PENDING')
                                <span class="pill" style="background: #FFF3E0; color: #E65100; padding: 0.75rem 1rem;">⏳ Pending</span>
                            @else
                                <span class="pill" style="background: #FFEBEE; color: #C62828; padding: 0.75rem 1rem;">✗ {{ $booking->payment_status }}</span>
                            @endif
                        </div>

                        <div style="border-top: 1px solid #E0E0E0; padding-top: 1rem;">
                            <p class="label" style="margin: 0 0 0.5rem;">Total Amount</p>
                            <p style="margin: 0; font-size: 2rem; font-weight: 700; color: var(--brand-primary, #652482);">{{ number_format($booking->total_amount) }} KES</p>
                        </div>

                        <div>
                            <p class="label" style="margin: 0 0 0.25rem;">Amount Paid</p>
                            <p style="margin: 0; font-weight: 600;">{{ number_format($booking->amount_paid) }} KES</p>
                        </div>

                        @if($booking->mpesa_receipt_number)
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem;">M-PESA Receipt</p>
                                <code style="background: #E8F5E9; padding: 0.5rem 1rem; border-radius: 6px; display: inline-block;">{{ $booking->mpesa_receipt_number }}</code>
                            </div>
                        @endif

                        @if($booking->payment_method)
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem;">Payment Method</p>
                                <p style="margin: 0;">{{ strtoupper($booking->payment_method) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Property Owner -->
                @if($booking->property && $booking->property->owner)
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-head">
                        <h3>Property Owner</h3>
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: 600;">{{ $booking->property->owner->name }}</p>
                        <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $booking->property->owner->email }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Info Banner -->
        <div style="background: #E3F2FD; border-left: 4px solid #1565C0; padding: 1rem; margin-top: 1.5rem; border-radius: 4px;">
            <p style="margin: 0; color: #1565C0; font-size: 0.875rem;"><strong>ℹ️ Note:</strong> This is a read-only view. Staff cannot modify bookings. Contact an admin for changes or escalations.</p>
        </div>
    </div>
@endsection
