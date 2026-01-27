@extends('layouts.velzon.app')

@section('title', 'Staff Dashboard')
@section('page-title', 'Staff Dashboard')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Front desk view</p>
                <h1>Today’s operations</h1>
                <p class="lede">Only the bookings you need to manage, nothing else.</p>
            </div>
            <div class="pill">{{ $today->format('M d, Y') }}</div>
        </div>

        <div class="metrics">
            <div class="card metric">
                <p class="label">Today’s check-ins</p>
                <p class="value">{{ number_format($stats['today_count']) }}</p>
                <p class="sub">Confirmed guests arriving</p>
            </div>
            <div class="card metric">
                <p class="label">Upcoming check-ins</p>
                <p class="value">{{ number_format($stats['upcoming_count']) }}</p>
                <p class="sub">Next arrivals</p>
            </div>
        </div>

        <div class="grid">
            <div class="card wide">
                <div class="card-head">
                    <div>
                        <h3>Today’s bookings</h3>
                        <p class="muted">Confirmed and ready for arrival.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Guest</th>
                                <th>Property</th>
                                <th>Dates</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($todaysBookings as $booking)
                                <tr>
                                    <td>{{ $booking->guest->name ?? 'Guest' }}</td>
                                    <td>{{ $booking->property->name ?? 'Property' }}</td>
                                    <td>{{ optional($booking->check_in)->format('M d') }} – {{ optional($booking->check_out)->format('M d') }}</td>
                                    <td><span class="status status-paid">Confirmed</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No check-ins today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card stack">
                <div class="card-head">
                    <h3>Upcoming arrivals</h3>
                    <p class="muted">Confirmed guests to prepare for.</p>
                </div>
                <div class="list">
                    @forelse ($upcomingCheckins as $booking)
                        <div class="list-item">
                            <div>
                                <p class="label mb-1">{{ $booking->guest->name ?? 'Guest' }}</p>
                                <p class="muted mb-0">{{ optional($booking->check_in)->format('M d') }} · {{ $booking->property->name ?? 'Property' }}</p>
                            </div>
                            <span class="pill light">{{ $booking->booking_ref }}</span>
                        </div>
                    @empty
                        <p class="muted mb-0">No upcoming check-ins.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    :root {
        --brand-primary: #652482;
        --brand-bg: #f8f5f0;
        --brand-text: #222222;
        --brand-accent: #decfbc;
    }

    body { background: var(--brand-bg); color: var(--brand-text); }

    .dash-shell { display: flex; flex-direction: column; gap: 24px; }
    .dash-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
    .eyebrow { text-transform: uppercase; letter-spacing: 0.08em; font-size: 12px; margin: 0 0 6px; color: #5b5566; }
    .dash-head h1 { margin: 0 0 6px; font-weight: 700; }
    .lede { margin: 0; color: #4a4550; }
    .pill { background: var(--brand-accent); color: var(--brand-text); padding: 10px 14px; border-radius: 999px; font-weight: 600; }

    .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
    .card { background: white; border: 1px solid rgba(0,0,0,0.04); border-radius: 18px; padding: 18px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
    .metric .label { margin: 0 0 6px; color: #5a5661; }
    .metric .value { margin: 0; font-size: 32px; font-weight: 700; color: var(--brand-primary); }
    .metric .sub { margin: 4px 0 0; color: #6f6a75; }

    .grid { display: grid; grid-template-columns: 2fr 1fr; gap: 18px; }
    .card-head h3 { margin: 0; }
    .card-head .muted { margin: 4px 0 0; }
    .muted { color: #6d6673; }
    .wide { padding: 18px 18px 12px; }
    .stack { display: flex; flex-direction: column; gap: 16px; }

    .pill.light { background: rgba(101,36,130,0.08); color: var(--brand-primary); padding: 6px 10px; border-radius: 12px; font-weight: 600; }
    .status { padding: 6px 10px; border-radius: 12px; font-weight: 600; font-size: 12px; text-transform: capitalize; }
    .status-paid { background: rgba(60,179,113,0.12); color: #2d8658; }

    .list { display: flex; flex-direction: column; gap: 10px; }
    .list-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(0,0,0,0.06); }
    .list-item:last-child { border-bottom: none; }

    @media (max-width: 992px) {
        .grid { grid-template-columns: 1fr; }
        .dash-head { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush
