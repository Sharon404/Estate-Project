@extends('layouts.velzon.app')

@section('title', 'Payment Verification')
@section('page-title', 'Payment Verification')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Staff Portal</p>
                <h1>Payment Verification</h1>
                <p class="lede">Verify M-PESA payment receipts</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ $stats['pending'] ?? 0 }}</p>
                <p class="label">Pending Verification</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['verified_today'] ?? 0 }}</p>
                <p class="label">Verified Today</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['your_verifications'] ?? 0 }}</p>
                <p class="label">Your Verifications</p>
            </div>
        </div>

        <!-- Pending Verifications -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Pending Verifications ({{ $submissions->total() }})</h3>
                <p class="muted">Submissions awaiting M-PESA confirmation</p>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Submission ID</th>
                            <th>Booking</th>
                            <th>Guest</th>
                            <th>Amount</th>
                            <th>M-PESA Code</th>
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($submissions as $submission)
                            <tr>
                                <td><code>#{{ $submission->id }}</code></td>
                                <td><code>#{{ $submission->booking->id ?? 'N/A' }}</code></td>
                                <td>{{ $submission->booking->guest->name ?? 'N/A' }}</td>
                                <td><strong>{{ number_format($submission->amount) }} KES</strong></td>
                                <td>
                                    @if($submission->mpesa_code)
                                        <code style="background: #FFF3E0; padding: 0.25rem 0.5rem; border-radius: 4px;">{{ $submission->mpesa_code }}</code>
                                    @else
                                        <span class="text-muted">No code</span>
                                    @endif
                                </td>
                                <td>{{ $submission->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('staff.verification.show', $submission) }}" class="pill" style="background: var(--brand-primary, #652482); color: white; text-decoration: none; font-size: 0.875rem;">Verify</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 3rem;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2" style="margin: 0 auto 1rem;">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                    <p style="margin: 0; color: #2E7D32; font-weight: 600;">All caught up!</p>
                                    <p style="margin: 0.5rem 0 0; color: #5a5661; font-size: 0.875rem;">No pending verifications at the moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($submissions->hasPages())
                <div class="card-footer">
                    {{ $submissions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
