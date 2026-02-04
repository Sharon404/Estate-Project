@extends('layouts.velzon.app')

@section('title', 'Payouts')
@section('page-title', 'Payouts')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Financial Management</p>
                <h1>Payouts</h1>
                <p class="lede">Manage property owner and staff payouts</p>
            </div>
            <a href="{{ route('admin.payouts.create') }}" class="pill" style="background: var(--brand-primary, #652482); color: white; text-decoration: none; cursor: pointer;">Create Payout</a>
        </div>

        <!-- Stats -->
        <div class="metrics-grid">
            <div class="chip">
                <p class="metric">{{ $stats['pending'] ?? 0 }}</p>
                <p class="label">Pending</p>
            </div>
            <div class="chip">
                <p class="metric">{{ $stats['approved'] ?? 0 }}</p>
                <p class="label">Approved</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($stats['total_pending_amount'] ?? 0) }} KES</p>
                <p class="label">Pending Amount</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($stats['total_completed_amount'] ?? 0) }} KES</p>
                <p class="label">Completed Amount</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.payouts.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                        <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                        <option value="DISPUTED" {{ request('status') == 'DISPUTED' ? 'selected' : '' }}>Disputed</option>
                    </select>
                </div>
                <div>
                    <label class="label">Payee Type</label>
                    <select name="payee_type" class="form-control">
                        <option value="">All Types</option>
                        <option value="OWNER" {{ request('payee_type') == 'OWNER' ? 'selected' : '' }}>Owner</option>
                        <option value="STAFF" {{ request('payee_type') == 'STAFF' ? 'selected' : '' }}>Staff</option>
                        <option value="COMMISSION" {{ request('payee_type') == 'COMMISSION' ? 'selected' : '' }}>Commission</option>
                    </select>
                </div>
                <div>
                    <label class="label">Payee</label>
                    <input type="text" name="payee" class="form-control" placeholder="Search payee..." value="{{ request('payee') }}">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.payouts.index') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Payouts Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Payout Ref</th>
                            <th>Payee</th>
                            <th>Type</th>
                            <th>Gross Amount</th>
                            <th>Net Amount</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr>
                                <td><code>{{ $payout->payout_ref }}</code></td>
                                <td>{{ $payout->payee->name ?? 'N/A' }}</td>
                                <td><span class="pill light">{{ $payout->payee_type }}</span></td>
                                <td>{{ number_format($payout->gross_amount) }} KES</td>
                                <td><strong>{{ number_format($payout->net_amount) }} KES</strong></td>
                                <td>
                                    @if($payout->status == 'PENDING')
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">Pending</span>
                                    @elseif($payout->status == 'APPROVED')
                                        <span class="pill" style="background: #E3F2FD; color: #1565C0;">Approved</span>
                                    @elseif($payout->status == 'COMPLETED')
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Completed</span>
                                    @else
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">Disputed</span>
                                    @endif
                                </td>
                                <td>{{ $payout->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.payouts.show', $payout) }}" class="link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No payouts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($payouts->hasPages())
                <div class="card-footer">
                    {{ $payouts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
