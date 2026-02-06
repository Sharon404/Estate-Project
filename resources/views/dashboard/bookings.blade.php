@extends('layouts.velzon.app')

@section('title', 'Bookings')
@section('page-title', 'Bookings Management')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Reservations</p>
                <h1>All Bookings</h1>
                <p class="lede">View and manage all guest bookings and reservations.</p>
            </div>
            <div class="pill">{{ $bookings->total() }} total</div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" class="filter-form">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Status</label>
                        <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @if(request('status') == $status) selected @endif>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem;">Guest Name / Email</label>
                        <input type="text" name="guest" placeholder="Search..." value="{{ request('guest') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <button type="submit" style="padding: 0.5rem 1rem; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.bookings') }}" style="padding: 0.5rem 1rem; background: #f0f0f0; color: #333; border: none; border-radius: 4px; cursor: pointer; text-align: center; text-decoration: none;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="card">
            <div class="card-head">
                <h3>Bookings Overview</h3>
                <p class="muted">Complete list of all reservations</p>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Guest</th>
                            <th>House</th>
                            <th>Check-in / Check-out</th>
                            <th>Nights</th>
                            <th>Total Amount</th>
                            <th>Booking Status</th>
                            <th>Payment Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>
                                    <strong style="font-family: monospace; color: #1976D2;">{{ $booking->booking_ref }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $booking->guest->full_name }}</strong>
                                        <br>
                                        <small style="color: #999;">{{ $booking->guest->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $booking->property->name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <small>
                                        {{ $booking->check_in->format('M d, Y') }}<br>
                                        to {{ $booking->check_out->format('M d, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $booking->nights }}</strong>
                                </td>
                                <td>
                                    <strong>KES {{ number_format($booking->total_amount, 2) }}</strong>
                                </td>
                                <td>
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
                                    <span style="{{ $statusColors[$booking->status] ?? '' }} padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">
                                        {{ str_replace('_', ' ', $booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $paymentStatus = $booking->amount_paid >= $booking->total_amount ? 'PAID' : ($booking->amount_paid > 0 ? 'PARTIAL' : 'UNPAID');
                                        $paymentColors = [
                                            'PAID' => 'background: #C8E6C9; color: #2E7D32;',
                                            'PARTIAL' => 'background: #FFF9C4; color: #F57F17;',
                                            'UNPAID' => 'background: #FFCDD2; color: #C62828;',
                                        ];
                                    @endphp
                                    <span style="{{ $paymentColors[$paymentStatus] ?? '' }} padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.75rem; font-weight: bold;">
                                        {{ $paymentStatus }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $booking->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                        <a href="{{ route('admin.booking-detail', $booking) }}" style="color: #2196F3; text-decoration: none; font-weight: bold;">View</a>
                                        <span style="color: #ccc;">|</span>
                                        <a href="{{ route('admin.bookings.edit', $booking) }}" style="color: #2E7D32; text-decoration: none; font-weight: bold;">Edit</a>
                                        <span style="color: #ccc;">|</span>
                                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Delete this booking? This action cannot be undone.');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #D32F2F; font-weight: bold; cursor: pointer; padding: 0;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 2rem; color: #999;">
                                    No bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div style="padding: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .dash-shell { max-width: 1400px; margin: 0 auto; }
        .dash-head { margin-bottom: 2rem; }
        .dash-head h1 { font-size: 2rem; margin: 0.5rem 0; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; }
        .card-head { margin-bottom: 1rem; }
        .card-head h3 { margin: 0 0 0.25rem 0; font-size: 1.1rem; }
        .card-head .muted { margin: 0; font-size: 0.875rem; color: #999; }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table thead { background: #f5f5f5; }
        .table th { padding: 0.75rem; text-align: left; font-weight: 600; font-size: 0.875rem; }
        .table td { padding: 0.75rem; border-bottom: 1px solid #e0e0e0; }
    </style>
@endsection
