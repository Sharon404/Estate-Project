@extends('layouts.velzon.app')

@section('title', 'Refund Details')
@section('page-title', 'Refund Details')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Payment Management</p>
                <h1>Refund Details</h1>
                <p class="lede">Booking #{{ $refund->booking_id }}</p>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Refund Information -->
            <div class="card">
                <div class="card-head">
                    <h3>Refund Information</h3>
                </div>
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Refund Amount</p>
                        <p style="margin: 0; font-size: 2rem; font-weight: 700; color: var(--brand-primary, #652482);">{{ number_format($refund->amount) }} KES</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Reason</p>
                        <p style="margin: 0; line-height: 1.6;">{{ $refund->reason }}</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Requested By</p>
                        <p style="margin: 0; font-weight: 600;">{{ $refund->requester->name ?? 'N/A' }}</p>
                        <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $refund->requester->email ?? '' }}</p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Requested Date</p>
                            <p style="margin: 0;">{{ $refund->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if(isset($refund->approved_at) && $refund->approved_at)
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Approved Date</p>
                                <p style="margin: 0;">{{ $refund->approved_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                    @if($refund->approved_by)
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Approved By</p>
                            <p style="margin: 0; font-weight: 600;">{{ $refund->approver->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                    @if($refund->mpesa_ref)
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">M-PESA Reference</p>
                            <code style="background: #E8F5E9; padding: 0.5rem 1rem; border-radius: 6px; display: inline-block;">{{ $refund->mpesa_ref }}</code>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Status & Actions -->
            <div>
                <div class="card">
                    <div class="card-head">
                        <h3>Status</h3>
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div>
                            @if($refund->status == 'PENDING')
                                <span class="pill" style="background: #FFF3E0; color: #E65100; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">⏳ Pending</span>
                            @elseif($refund->status == 'APPROVED')
                                <span class="pill" style="background: #E3F2FD; color: #1565C0; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">✓ Approved</span>
                            @elseif($refund->status == 'REJECTED')
                                <span class="pill" style="background: #FFEBEE; color: #C62828; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">✗ Rejected</span>
                            @else
                                <span class="pill" style="background: #E8F5E9; color: #2E7D32; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">✓ Processed</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($refund->status == 'PENDING')
                    <div class="card" style="margin-top: 1.5rem;">
                        <div class="card-head">
                            <h3>Actions</h3>
                        </div>
                        <div style="display: grid; gap: 0.75rem;">
                            <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST">
                                @csrf
                                <button type="submit" class="pill" style="background: #2E7D32; color: white; border: none; width: 100%; justify-content: center; cursor: pointer;" onclick="return confirm('Approve this refund request?')">✓ Approve Refund</button>
                            </form>
                            <form action="{{ route('admin.refunds.reject', $refund) }}" method="POST">
                                @csrf
                                <button type="submit" class="pill" style="background: #C62828; color: white; border: none; width: 100%; justify-content: center; cursor: pointer;" onclick="return confirm('Reject this refund request?')">✗ Reject Refund</button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($refund->status == 'APPROVED')
                    <div class="card" style="margin-top: 1.5rem;">
                        <div class="card-head">
                            <h3>Mark as Processed</h3>
                        </div>
                        <form action="{{ route('admin.refunds.processed', $refund) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 1rem;">
                                <label class="label">M-PESA Reference</label>
                                <input type="text" name="mpesa_ref" class="form-control" placeholder="Enter M-PESA ref..." required>
                            </div>
                            <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; width: 100%; justify-content: center; cursor: pointer;">Mark Processed</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Booking -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-head">
                <h3>Related Booking</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Property</th>
                            <th>Guest</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($refund->booking)
                            <tr>
                                <td><code>#{{ $refund->booking->id }}</code></td>
                                <td>{{ $refund->booking->property->name ?? 'N/A' }}</td>
                                <td>{{ $refund->booking->guest->name ?? 'N/A' }}</td>
                                <td>{{ $refund->booking->check_in_date ? \Carbon\Carbon::parse($refund->booking->check_in_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $refund->booking->check_out_date ? \Carbon\Carbon::parse($refund->booking->check_out_date)->format('M d, Y') : 'N/A' }}</td>
                                <td><strong>{{ number_format($refund->booking->total_amount) }} KES</strong></td>
                                <td><span class="pill light">{{ $refund->booking->status }}</span></td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="7" class="text-center text-muted py-3">Booking not found</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
