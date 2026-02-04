@extends('layouts.velzon.app')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Staff Portal</p>
                <h1>Bookings</h1>
                <p class="lede">View all property bookings (read-only)</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('staff.bookings.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
                        <option value="CHECKED_IN" {{ request('status') == 'CHECKED_IN' ? 'selected' : '' }}>Checked In</option>
                        <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="label">Guest Name</label>
                    <input type="text" name="guest" class="form-control" placeholder="Search guest..." value="{{ request('guest') }}">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Filter</button>
                    <a href="{{ route('staff.bookings.index') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="card">
            <div class="card-head">
                <h3>All Bookings ({{ $bookings->total() }})</h3>
                <p class="muted" style="font-size: 0.875rem;">Read-only access</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest</th>
                            <th>Property</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td><code>#{{ $booking->id }}</code></td>
                                <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                <td>{{ $booking->property->name ?? 'N/A' }}</td>
                                <td>{{ $booking->check_in_date ? \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $booking->check_out_date ? \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') : 'N/A' }}</td>
                                <td><strong>{{ number_format($booking->total_amount) }} KES</strong></td>
                                <td>
                                    @if($booking->status == 'CONFIRMED')
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Confirmed</span>
                                    @elseif($booking->status == 'PENDING')
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">Pending</span>
                                    @elseif($booking->status == 'CHECKED_IN')
                                        <span class="pill" style="background: #E3F2FD; color: #1565C0;">Checked In</span>
                                    @elseif($booking->status == 'COMPLETED')
                                        <span class="pill light">Completed</span>
                                    @else
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('staff.bookings.show', $booking) }}" class="link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="card-footer">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
