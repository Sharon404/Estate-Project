@extends('layouts.velzon.app')

@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Front desk operations</p>
                <h1>Today's Schedule</h1>
                <p class="lede">Check-ins, check-outs, and upcoming bookings you need to manage.</p>
            </div>
            <div class="pill">{{ $today->format('M d, Y') }}</div>
        </div>

        <div class="metrics">
            <div class="card metric">
                <p class="label">Today's Check-ins</p>
                <p class="value">{{ $stats['today_checkins'] }}</p>
                <p class="sub">Guests arriving</p>
            </div>
            <div class="card metric">
                <p class="label">Today's Check-outs</p>
                <p class="value">{{ $stats['today_checkouts'] }}</p>
                <p class="sub">Guests departing</p>
            </div>
            <div class="card metric">
                <p class="label">Upcoming Check-ins</p>
                <p class="value">{{ $stats['upcoming_checkins'] }}</p>
                <p class="sub">Next 7 days</p>
            </div>
            <div class="card metric">
                <p class="label">Upcoming Check-outs</p>
                <p class="value">{{ $stats['upcoming_checkouts'] }}</p>
                <p class="sub">Next 7 days</p>
            </div>
        </div>

        <div class="grid">
            <!-- Today's Check-ins -->
            <div class="card">
                <div class="card-head">
                    <h3>Today's Check-ins</h3>
                    <p class="muted">Guests arriving today</p>
                </div>
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.875rem;">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>House</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todaysCheckins as $booking)
                                <tr>
                                    <td><strong>{{ $booking->guest->full_name }}</strong></td>
                                    <td><small>{{ $booking->property->name ?? 'N/A' }}</small></td>
                                    <td><small>{{ $booking->check_in->format('H:i') }}</small></td>
                                    <td><span style="background: #E8F5E9; color: #2E7D32; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 1rem; color: #999;">No check-ins today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Today's Check-outs -->
            <div class="card">
                <div class="card-head">
                    <h3>Today's Check-outs</h3>
                    <p class="muted">Guests departing today</p>
                </div>
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.875rem;">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>House</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todaysCheckouts as $booking)
                                <tr>
                                    <td><strong>{{ $booking->guest->full_name }}</strong></td>
                                    <td><small>{{ $booking->property->name ?? 'N/A' }}</small></td>
                                    <td><small>{{ $booking->check_out->format('H:i') }}</small></td>
                                    <td><span style="background: #FFF3E0; color: #E65100; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Checkout</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 1rem; color: #999;">No check-outs today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Check-ins -->
            <div class="card">
                <div class="card-head">
                    <h3>Upcoming Check-ins (Next 7 Days)</h3>
                    <p class="muted">Prepare for arrivals</p>
                </div>
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.875rem;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Guest</th>
                                <th>House</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingCheckins as $booking)
                                <tr>
                                    <td><small><strong>{{ $booking->check_in->format('M d') }}</strong></small></td>
                                    <td><small>{{ $booking->guest->full_name }}</small></td>
                                    <td><small>{{ $booking->property->name ?? 'N/A' }}</small></td>
                                    <td><span style="background: #E3F2FD; color: #1565C0; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">{{ $booking->nights }} nights</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 1rem; color: #999;">No upcoming check-ins</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Upcoming Check-outs -->
            <div class="card">
                <div class="card-head">
                    <h3>Upcoming Check-outs (Next 7 Days)</h3>
                    <p class="muted">Prepare for departures</p>
                </div>
                <div class="table-responsive">
                    <table class="table" style="font-size: 0.875rem;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Guest</th>
                                <th>House</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingCheckouts as $booking)
                                <tr>
                                    <td><small><strong>{{ $booking->check_out->format('M d') }}</strong></small></td>
                                    <td><small>{{ $booking->guest->full_name }}</small></td>
                                    <td><small>{{ $booking->property->name ?? 'N/A' }}</small></td>
                                    <td><span style="background: #FFF9C4; color: #F57F17; padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">Departing</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 1rem; color: #999;">No upcoming check-outs</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .dash-shell { max-width: 1400px; margin: 0 auto; }
        .dash-head { margin-bottom: 2rem; }
        .dash-head h1 { font-size: 2rem; margin: 0.5rem 0; }
        .eyebrow { text-transform: uppercase; letter-spacing: 0.08em; font-size: 12px; margin: 0 0 6px; color: #999; }
        .pill { display: inline-block; background: #E3F2FD; color: #1565C0; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; }
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
        .card { border: 1px solid #e0e0e0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .card.metric { padding: 1.5rem; }
        .card.metric .label { font-size: 0.875rem; color: #666; margin: 0; }
        .card.metric .value { font-size: 1.75rem; font-weight: bold; margin: 0.5rem 0; }
        .card.metric .sub { font-size: 0.75rem; color: #999; margin: 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; }
        .card-head { margin-bottom: 1rem; }
        .card-head h3 { margin: 0 0 0.25rem 0; font-size: 1rem; }
        .card-head .muted { margin: 0; font-size: 0.875rem; color: #999; }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; border-collapse: collapse; }
        .table thead { background: #f5f5f5; }
        .table th { padding: 0.5rem; text-align: left; font-weight: 600; font-size: 0.875rem; }
        .table td { padding: 0.5rem; border-bottom: 1px solid #f0f0f0; }
        .muted { color: #999; }
    </style>
@endsection
