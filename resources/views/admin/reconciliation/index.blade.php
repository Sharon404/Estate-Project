@extends('layouts.velzon.app')

@section('title', 'Payment Reconciliation')
@section('page-title', 'Payment Reconciliation')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Financial Management</p>
                <h1>Payment Reconciliation</h1>
                <p class="lede">Identify and resolve payment discrepancies</p>
            </div>
        </div>

        <!-- Issue Summary -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ count($mismatches ?? []) }}</p>
                <p class="label">Amount Mismatches</p>
            </div>
            <div class="chip">
                <p class="metric">{{ count($pending ?? []) }}</p>
                <p class="label">Pending Verification</p>
            </div>
            <div class="chip">
                <p class="metric">{{ count($failed ?? []) }}</p>
                <p class="label">Failed Payments</p>
            </div>
            <div class="chip">
                <p class="metric">{{ count($duplicates ?? []) }}</p>
                <p class="label">Duplicate Receipts</p>
            </div>
        </div>

        <!-- Amount Mismatches -->
        @if(count($mismatches ?? []) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <div>
                    <h3>Amount Mismatches</h3>
                    <p class="muted">Bookings where paid amount differs from total amount</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Property</th>
                            <th>Total Amount</th>
                            <th>Amount Paid</th>
                            <th>Difference</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mismatches as $booking)
                            <tr>
                                <td><code>#{{ $booking->id }}</code></td>
                                <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                <td>{{ $booking->property->name ?? 'N/A' }}</td>
                                <td>{{ number_format($booking->total_amount) }} KES</td>
                                <td>{{ number_format($booking->amount_paid) }} KES</td>
                                <td>
                                    @php $diff = $booking->total_amount - $booking->amount_paid; @endphp
                                    <span style="color: {{ $diff > 0 ? '#C62828' : '#2E7D32' }}; font-weight: 600;">
                                        {{ $diff > 0 ? '-' : '+' }}{{ number_format(abs($diff)) }} KES
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('admin.reconciliation.resolve', $booking) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <select name="action" class="form-control" style="width: auto; display: inline-block; font-size: 0.875rem;" onchange="if(confirm('Resolve this mismatch?')) this.form.submit();">
                                            <option value="">Resolve...</option>
                                            <option value="adjust_paid">Adjust Paid Amount</option>
                                            <option value="adjust_total">Adjust Total Amount</option>
                                            <option value="mark_correct">Mark as Correct</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Pending Verification -->
        @if(count($pending ?? []) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <div>
                    <h3>Pending Verification</h3>
                    <p class="muted">Payments awaiting M-PESA verification</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Amount</th>
                            <th>M-PESA Code</th>
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending as $booking)
                            <tr>
                                <td><code>#{{ $booking->id }}</code></td>
                                <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                <td>{{ number_format($booking->total_amount) }} KES</td>
                                <td><code>{{ $booking->mpesa_receipt_number ?? 'N/A' }}</code></td>
                                <td>{{ $booking->updated_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('staff.verification.show', $booking) }}" class="link">Verify</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Failed Payments -->
        @if(count($failed ?? []) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <div>
                    <h3>Failed Payments</h3>
                    <p class="muted">Payment intents that failed to complete</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($failed as $booking)
                            <tr>
                                <td><code>#{{ $booking->id }}</code></td>
                                <td>{{ $booking->guest->name ?? 'N/A' }}</td>
                                <td>{{ number_format($booking->total_amount) }} KES</td>
                                <td><span class="pill" style="background: #FFEBEE; color: #C62828;">{{ $booking->payment_status }}</span></td>
                                <td>{{ $booking->updated_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Duplicate Receipts -->
        @if(count($duplicates ?? []) > 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <div>
                    <h3>Duplicate Receipt Numbers</h3>
                    <p class="muted">Same M-PESA code used on multiple bookings</p>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>M-PESA Code</th>
                            <th>Booking Count</th>
                            <th>Total Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($duplicates as $receipt => $bookings)
                            <tr>
                                <td><code>{{ $receipt }}</code></td>
                                <td>{{ count($bookings) }} bookings</td>
                                <td>{{ number_format($bookings->sum('total_amount')) }} KES</td>
                                <td>
                                    <button class="link" onclick="alert('Bookings: {{ $bookings->pluck('id')->join(', ') }}')">View Details</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        @if(count($mismatches ?? []) == 0 && count($pending ?? []) == 0 && count($failed ?? []) == 0 && count($duplicates ?? []) == 0)
        <div class="card" style="margin-top: 1.5rem;">
            <div style="text-align: center; padding: 3rem;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2" style="margin: 0 auto 1rem;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <h3 style="margin: 0 0 0.5rem; color: #2E7D32;">All Clear!</h3>
                <p style="margin: 0; color: #5a5661;">No payment discrepancies found. All transactions are reconciled.</p>
            </div>
        </div>
        @endif
    </div>
@endsection
