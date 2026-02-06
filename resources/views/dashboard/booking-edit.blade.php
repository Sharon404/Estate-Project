@extends('layouts.velzon.app')

@section('title', 'Edit Booking')
@section('page-title', 'Edit Booking')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Booking Reference</p>
                <h1>{{ $booking->booking_ref }}</h1>
                <p class="lede">Update booking details, payments, and status.</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.booking-detail', $booking) }}" style="padding: 0.5rem 1rem; background: #2196F3; color: white; border: none; border-radius: 4px; text-decoration: none;">← Back</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="card" style="border-left: 4px solid #D32F2F;">
                <strong style="color: #D32F2F;">Please fix the errors below:</strong>
                <ul style="margin: 0.5rem 0 0 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
                <div>
                    <div class="card">
                        <div class="card-head">
                            <h3>Guest & Property</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div>
                                <small style="color: #999;">Guest</small>
                                <p style="margin: 0.25rem 0; font-weight: bold;">{{ $booking->guest->full_name }}</p>
                                <small>{{ $booking->guest->email }}</small>
                            </div>
                            <div>
                                <small style="color: #999;">Property</small>
                                <p style="margin: 0.25rem 0; font-weight: bold;">{{ $booking->property->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-head">
                            <h3>Reservation Details</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Check In</label>
                                <input type="date" name="check_in" value="{{ old('check_in', $booking->check_in?->format('Y-m-d')) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Check Out</label>
                                <input type="date" name="check_out" value="{{ old('check_out', $booking->check_out?->format('Y-m-d')) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Homes (Rooms)</label>
                                <input type="number" name="rooms" min="1" max="10" value="{{ old('rooms', $booking->rooms) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Adults</label>
                                <input type="number" name="adults" min="1" max="20" value="{{ old('adults', $booking->adults) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Children</label>
                                <input type="number" name="children" min="0" max="20" value="{{ old('children', $booking->children) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-head">
                            <h3>Payment Details</h3>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Nightly Rate</label>
                                <input type="number" step="0.01" name="nightly_rate" value="{{ old('nightly_rate', $booking->nightly_rate) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Total Amount</label>
                                <input type="number" step="0.01" name="total_amount" value="{{ old('total_amount', $booking->total_amount) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Amount Paid</label>
                                <input type="number" step="0.01" name="amount_paid" value="{{ old('amount_paid', $booking->amount_paid) }}" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card">
                        <div class="card-head">
                            <h3>Status</h3>
                        </div>
                        <div style="display: grid; gap: 1rem;">
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Booking Status</label>
                                <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" @if(old('status', $booking->status) === $status) selected @endif>
                                            {{ str_replace('_', ' ', $status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="display:block; font-size: 0.875rem; margin-bottom: 0.5rem;">Special Requests</label>
                                <textarea name="special_requests" rows="6" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">{{ old('special_requests', $booking->special_requests) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-head">
                            <h3>Actions</h3>
                        </div>
                        <div style="display: grid; gap: 0.75rem;">
                            <button type="submit" style="padding: 0.75rem 1rem; background: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer;">Save Changes</button>
                            <a href="{{ route('admin.booking-detail', $booking) }}" style="padding: 0.75rem 1rem; background: #f0f0f0; color: #333; border: none; border-radius: 4px; text-align: center; text-decoration: none;">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .dash-shell { max-width: 1200px; margin: 0 auto; }
        .dash-head { display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem; }
        .dash-head h1 { font-size: 2rem; margin: 0.5rem 0; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card-head { margin-bottom: 1rem; }
        .card-head h3 { margin: 0; font-size: 1rem; }
    </style>
@endsection
