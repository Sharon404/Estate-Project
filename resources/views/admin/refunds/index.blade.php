@extends('layouts.velzon.app')

@section('title', 'Refunds')
@section('page-title', 'Refunds')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Payment Management</p>
                <h1>Refunds</h1>
                <p class="lede">Manage customer refund requests</p>
            </div>
            <a href="{{ route('admin.refunds.create') }}" class="pill" style="background: var(--brand-primary, #652482); color: white; text-decoration: none; cursor: pointer;">Create Refund</a>
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
                <p class="metric">{{ $stats['processed'] ?? 0 }}</p>
                <p class="label">Processed</p>
            </div>
            <div class="chip">
                <p class="metric">{{ number_format($stats['total_amount'] ?? 0) }} KES</p>
                <p class="label">Total Amount</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="{{ route('admin.refunds.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label class="label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All Statuses</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                        <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Rejected</option>
                        <option value="PROCESSED" {{ request('status') == 'PROCESSED' ? 'selected' : '' }}>Processed</option>
                    </select>
                </div>
                <div>
                    <label class="label">User</label>
                    <input type="text" name="user" class="form-control" placeholder="Search user..." value="{{ request('user') }}">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; cursor: pointer;">Filter</button>
                    <a href="{{ route('admin.refunds.index') }}" class="pill light" style="text-decoration: none; display: inline-flex; align-items: center;">Reset</a>
                </div>
            </form>
        </div>

        <!-- Refunds Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($refunds as $refund)
                            <tr>
                                <td><code>#{{ $refund->booking_id }}</code></td>
                                <td>{{ $refund->requester->name ?? 'N/A' }}</td>
                                <td><strong>{{ number_format($refund->amount) }} KES</strong></td>
                                <td>{{ Str::limit($refund->reason, 40) }}</td>
                                <td>
                                    @if($refund->status == 'PENDING')
                                        <span class="pill" style="background: #FFF3E0; color: #E65100;">Pending</span>
                                    @elseif($refund->status == 'APPROVED')
                                        <span class="pill" style="background: #E3F2FD; color: #1565C0;">Approved</span>
                                    @elseif($refund->status == 'REJECTED')
                                        <span class="pill" style="background: #FFEBEE; color: #C62828;">Rejected</span>
                                    @else
                                        <span class="pill" style="background: #E8F5E9; color: #2E7D32;">Processed</span>
                                    @endif
                                </td>
                                <td>{{ $refund->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.refunds.show', $refund) }}" class="link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">No refunds found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($refunds->hasPages())
                <div class="card-footer">
                    {{ $refunds->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
