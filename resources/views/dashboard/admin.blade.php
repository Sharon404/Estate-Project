@extends('layouts.velzon.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Control Center</p>
                <h1>Operations overview</h1>
                <p class="lede">Full-system visibility across bookings, revenue, and payments.</p>
            </div>
            <div class="pill">Updated {{ now()->format('M d, Y') }}</div>
        </div>

        <div class="metrics">
            <div class="card metric">
                <p class="label">Total Revenue</p>
                <p class="value">KES {{ number_format($totalRevenue, 2) }}</p>
                <p class="sub">This month</p>
            </div>
            <div class="card metric">
                <p class="label">Total Bookings</p>
                <p class="value">{{ number_format($totalBookings) }}</p>
                <p class="sub">This month</p>
            </div>
            <div class="card metric">
                <p class="label">Completed Payments</p>
                <p class="value">{{ number_format($completedPayments) }}</p>
                <p class="sub">Successful intents</p>
            </div>
            <div class="card metric">
                <p class="label">Pending / Failed</p>
                <p class="value">{{ number_format($pendingOrFailedPayments) }}</p>
                <p class="sub">Requires attention</p>
            </div>
            <div class="card metric">
                <p class="label">Avg Nights</p>
                <p class="value">{{ $avgNights }}</p>
                <p class="sub">Per booking</p>
            </div>
        </div>

        <div class="grid">
            <div class="card wide">
                <div class="card-head">
                    <div>
                        <h3>Recent bookings</h3>
                        <p class="muted">Latest activity across all properties.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Booking</th>
                                <th>Guest</th>
                                <th>Property</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td><span class="pill light">{{ $booking->booking_ref }}</span></td>
                                    <td>{{ $booking->guest->name ?? 'Guest' }}</td>
                                    <td>{{ $booking->property->name ?? 'Property' }}</td>
                                    <td>{{ optional($booking->check_in)->format('M d') }} – {{ optional($booking->check_out)->format('M d') }}</td>
                                    <td><span class="status status-{{ strtolower($booking->status) }}">{{ str_replace('_', ' ', $booking->status) }}</span></td>
                                    <td class="text-end">KES {{ number_format($booking->total_amount, 2) }}</td>
                                    <td class="text-end">
                                        <a class="link" href="{{ route('booking.summary', $booking->id) }}">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No bookings yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card stack">
                <div class="card-head">
                    <h3>Payment health</h3>
                    <p class="muted">Status across all payment intents.</p>
                </div>
                <div class="chip-grid">
                    @php
                        $statuses = $paymentMethodSummary->groupBy('status')->map(function($items) {
                            return (object)[
                                'status' => $items->first()->status,
                                'total' => $items->sum('total')
                            ];
                        });
                    @endphp
                    @foreach ($statuses as $row)
                        <div class="chip">
                            <span class="dot dot-{{ strtolower($row->status) }}"></span>
                            <div>
                                <p class="label">{{ ucfirst(strtolower(str_replace('_', ' ', $row->status))) }}</p>
                                <p class="value small">{{ $row->total }} intents</p>
                            </div>
                        </div>
                    @endforeach
                    @if ($statuses->isEmpty())
                        <p class="muted mb-0">No payment activity yet.</p>
                    @endif
                </div>

                <div class="divider"></div>
                <h4 class="mb-3">By method</h4>
                <div class="chip-grid">
                    @foreach ($paymentMethodSummary as $row)
                        <div class="chip">
                            <span class="dot"></span>
                            <div>
                                <p class="label">{{ $row->method }} / {{ ucfirst(strtolower($row->status)) }}</p>
                                <p class="value small">{{ $row->total }} intents</p>
                            </div>
                        </div>
                    @endforeach
                    @if ($paymentMethodSummary->isEmpty())
                        <p class="muted mb-0">No method breakdown yet.</p>
                    @endif
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

    .grid { display: grid; grid-template-columns: 1fr; gap: 18px; }
    .card-head h3 { margin: 0; }
    .card-head .muted { margin: 4px 0 0; }
    .muted { color: #6d6673; }
    .wide { padding: 18px 18px 12px; }
    .stack { display: flex; flex-direction: column; gap: 16px; }

    .pill.light { background: rgba(101,36,130,0.08); color: var(--brand-primary); padding: 6px 10px; border-radius: 12px; font-weight: 600; }
    .status { padding: 6px 10px; border-radius: 12px; font-weight: 600; font-size: 12px; text-transform: capitalize; }
    .status-paid { background: rgba(60,179,113,0.12); color: #2d8658; }
    .status-pending_payment { background: rgba(255,165,0,0.12); color: #b87400; }
    .status-partially_paid { background: rgba(255,215,0,0.16); color: #8a6d00; }
    .status-cancelled { background: rgba(220,53,69,0.12); color: #a12632; }
    .status-draft { background: rgba(108,117,125,0.12); color: #495057; }
    .status-expired { background: rgba(0,0,0,0.08); color: #444; }

    .chip-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; }
    .chip { border: 1px solid rgba(0,0,0,0.06); border-radius: 14px; padding: 10px 12px; display: flex; gap: 10px; align-items: center; background: #fff; box-shadow: 0 6px 16px rgba(0,0,0,0.04); }
    .chip .label { margin: 0; font-weight: 700; color: var(--brand-text); }
    .chip .value { margin: 2px 0 0; color: #6d6673; }
    .chip .value.small { font-size: 13px; }
    .dot { width: 12px; height: 12px; border-radius: 999px; background: var(--brand-primary); display: inline-block; }
    .dot-succeeded { background: #2d8658; }
    .dot-pending { background: #b87400; }
    .dot-failed { background: #a12632; }
    .dot-initiated { background: #4a5d8f; }
    .dot-under_review { background: #7a5a00; }
    .dot-cancelled { background: #6c757d; }

    .divider { height: 1px; background: rgba(0,0,0,0.06); margin: 6px 0; }
    .link { color: var(--brand-primary); font-weight: 700; text-decoration: none; }
    .link:hover { text-decoration: underline; }

    @media (max-width: 992px) {
        .grid { grid-template-columns: 1fr; }
        .dash-head { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush
