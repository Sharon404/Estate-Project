@extends('layouts.velzon.app')

@section('title', 'Booking Detail')
@section('page-title', $booking->booking_ref)

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Booking Reference</p>
                <h1>{{ $booking->booking_ref }}</h1>
                <p class="lede">Complete booking details and payment history.</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.bookings') }}" style="padding: 0.5rem 1rem; background: #2196F3; color: white; border: none; border-radius: 4px; text-decoration: none;">← Back</a>
                <a href="{{ route('admin.bookings.edit', $booking) }}" style="padding: 0.5rem 1rem; background: #2E7D32; color: white; border: none; border-radius: 4px; text-decoration: none;">Edit</a>
                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Delete this booking? This action cannot be undone.');" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 0.5rem 1rem; background: #D32F2F; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
                </form>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Left Column: Main Details -->
            <div>
                <!-- Guest Information -->
                <div class="card">
                    <div class="card-head">
                        <h3>Guest Information</h3>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <small style="color: #999;">Full Name</small>
                            <p style="margin: 0.25rem 0 1rem 0; font-weight: bold;">{{ $booking->guest->full_name }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Email</small>
                            <p style="margin: 0.25rem 0 1rem 0;">{{ $booking->guest->email }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Phone</small>
                            <p style="margin: 0.25rem 0 1rem 0;">{{ $booking->guest->phone_e164 ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Property & Dates -->
                <div class="card">
                    <div class="card-head">
                        <h3>Reservation Details</h3>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <small style="color: #999;">Property</small>
                            <p style="margin: 0.25rem 0 1rem 0; font-weight: bold;">{{ $booking->property->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Number of Homes</small>
                            <p style="margin: 0.25rem 0 1rem 0; font-weight: bold;">{{ $booking->rooms }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Check-in</small>
                            <p style="margin: 0.25rem 0 1rem 0; font-weight: bold;">{{ $booking->check_in->format('M d, Y @ H:i') }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Check-out</small>
                            <p style="margin: 0.25rem 0 1rem 0; font-weight: bold;">{{ $booking->check_out->format('M d, Y @ H:i') }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Number of Nights</small>
                            <p style="margin: 0.25rem 0 1rem 0; font-weight: bold;">{{ $booking->nights }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Guests</small>
                            <p style="margin: 0.25rem 0 1rem 0; font-weight: bold;">{{ $booking->adults }} Adults {{ $booking->children ? ', ' . $booking->children . ' Children' : '' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="card">
                    <div class="card-head">
                        <h3>Payment Summary</h3>
                    </div>
                    <div style="display: grid; gap: 0.75rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid #eee; padding-bottom: 0.75rem;">
                            <small style="color: #999;">Nightly Rate</small>
                            <strong>KES {{ number_format($booking->nightly_rate, 2) }}</strong>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid #eee; padding-bottom: 0.75rem;">
                            <small style="color: #999;">Nights × Homes</small>
                            <strong>{{ $booking->nights }} × {{ $booking->rooms }}</strong>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid #eee; padding-bottom: 0.75rem;">
                            <small style="color: #999;">Subtotal</small>
                            <strong>KES {{ number_format($booking->accommodation_subtotal, 2) }}</strong>
                        </div>
                        @if($booking->addons_subtotal > 0)
                            <div style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid #eee; padding-bottom: 0.75rem;">
                                <small style="color: #999;">Add-ons</small>
                                <strong>KES {{ number_format($booking->addons_subtotal, 2) }}</strong>
                            </div>
                        @endif
                        <div style="display: grid; grid-template-columns: 1fr 1fr; padding-top: 0.75rem; font-size: 1.1rem;">
                            <strong>Total Amount</strong>
                            <strong style="color: #1976D2;">KES {{ number_format($booking->total_amount, 2) }}</strong>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid #eee; padding-bottom: 0.75rem; margin-top: 0.75rem;">
                            <small style="color: #999;">Amount Paid</small>
                            <strong>KES {{ number_format($booking->amount_paid, 2) }}</strong>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; color: {{ $booking->amount_due > 0 ? '#D32F2F' : '#388E3C' }}; padding-top: 0.75rem; font-size: 1.1rem;">
                            <strong>Amount Due</strong>
                            <strong>KES {{ number_format($booking->amount_due, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Transactions -->
                @if($booking->bookingTransactions->count() > 0)
                    <div class="card">
                        <div class="card-head">
                            <h3>Payment Transactions</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table" style="font-size: 0.875rem;">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->bookingTransactions as $txn)
                                        <tr>
                                            <td>{{ $txn->posted_at->format('M d, H:i') }}</td>
                                            <td><strong>KES {{ number_format($txn->amount, 2) }}</strong></td>
                                            <td><small>{{ $txn->source }}</small></td>
                                            <td>
                                                <span style="background: #C8E6C9; color: #2E7D32; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Success</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Status & Info -->
            <div>
                <!-- Status -->
                <div class="card">
                    <div class="card-head">
                        <h3>Status</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            <small style="color: #999;">Booking Status</small>
                            @php
                                $statusColors = [
                                    'DRAFT' => 'background: #F3E5F5; color: #6A1B9A;',
                                    'PENDING_PAYMENT' => 'background: #FFF3E0; color: #E65100;',
                                    'PARTIALLY_PAID' => 'background: #FFF9C4; color: #F57F17;',
                                    'PAID' => 'background: #C8E6C9; color: #2E7D32;',
                                    'CANCELLED' => 'background: #FFEBEE; color: #C62828;',
                                    'EXPIRED' => 'background: #ECEFF1; color: #546E7A;',
                                ];
                            @endphp
                            <p style="margin: 0.5rem 0 0 0; display: inline-block; {{ $statusColors[$booking->status] ?? '' }} padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold;">
                                {{ str_replace('_', ' ', $booking->status) }}
                            </p>
                        </div>
                        <div>
                            <small style="color: #999;">Payment Status</small>
                            @php
                                $paymentStatus = $booking->amount_paid >= $booking->total_amount ? 'PAID' : ($booking->amount_paid > 0 ? 'PARTIAL' : 'UNPAID');
                                $paymentColors = [
                                    'PAID' => 'background: #C8E6C9; color: #2E7D32;',
                                    'PARTIAL' => 'background: #FFF9C4; color: #F57F17;',
                                    'UNPAID' => 'background: #FFCDD2; color: #C62828;',
                                ];
                            @endphp
                            <p style="margin: 0.5rem 0 0 0; display: inline-block; {{ $paymentColors[$paymentStatus] ?? '' }} padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold;">
                                {{ $paymentStatus }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="card">
                    <div class="card-head">
                        <h3>Metadata</h3>
                    </div>
                    <div style="display: grid; gap: 0.75rem; font-size: 0.875rem;">
                        <div>
                            <small style="color: #999;">Created</small>
                            <p style="margin: 0.25rem 0 0 0;">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Last Updated</small>
                            <p style="margin: 0.25rem 0 0 0;">{{ $booking->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <small style="color: #999;">Special Requests</small>
                            <p style="margin: 0.25rem 0 0 0; color: #666;">{{ $booking->special_requests ?? 'None' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dash-shell { max-width: 1200px; margin: 0 auto; }
        .dash-head { display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; }
        .dash-head h1 { font-size: 2rem; margin: 0.5rem 0; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-head { margin-bottom: 1rem; }
        .card-head h3 { margin: 0; font-size: 1rem; }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table th { padding: 0.5rem; text-align: left; font-weight: 600; border-bottom: 1px solid #e0e0e0; }
        .table td { padding: 0.5rem; border-bottom: 1px solid #f0f0f0; }
    </style>
@endsection
