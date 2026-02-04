@extends('layouts.velzon.app')

@section('title', 'Payout Details')
@section('page-title', 'Payout Details')

@section('content')
    <div class="dash-shell">
        <div class="dash-head">
            <div>
                <p class="eyebrow">Financial Management</p>
                <h1>Payout Details</h1>
                <p class="lede">{{ $payout->payout_ref }}</p>
            </div>
        </div>

        <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <!-- Payout Information -->
            <div class="card">
                <div class="card-head">
                    <h3>Payout Information</h3>
                </div>
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Payout Reference</p>
                        <code style="background: #F5F5F5; padding: 0.5rem 1rem; border-radius: 6px; display: inline-block; font-size: 1.125rem;">{{ $payout->payout_ref }}</code>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Gross Amount</p>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: 600;">{{ number_format($payout->gross_amount) }} KES</p>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Net Amount</p>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--brand-primary, #652482);">{{ number_format($payout->net_amount) }} KES</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Commission Amount</p>
                            <p style="margin: 0; color: #C62828; font-weight: 600;">-{{ number_format($payout->commission_amount) }} KES</p>
                        </div>
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Deductions</p>
                            <p style="margin: 0; color: #C62828; font-weight: 600;">-{{ number_format($payout->deductions ?? 0) }} KES</p>
                        </div>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Payee</p>
                        <p style="margin: 0; font-weight: 600; font-size: 1.125rem;">{{ $payout->payee->name ?? 'N/A' }}</p>
                        <p style="margin: 0.25rem 0 0; font-size: 0.875rem; color: #5a5661;">{{ $payout->payee->email ?? '' }}</p>
                    </div>
                    <div>
                        <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Payee Type</p>
                        <span class="pill light">{{ $payout->payee_type }}</span>
                    </div>
                    @if($payout->property_id)
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Related Property</p>
                            <p style="margin: 0; font-weight: 600;">{{ $payout->property->name ?? 'N/A' }}</p>
                        </div>
                    @endif
                    @if($payout->notes)
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Notes</p>
                            <p style="margin: 0; line-height: 1.6;">{{ $payout->notes }}</p>
                        </div>
                    @endif
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Created Date</p>
                            <p style="margin: 0;">{{ $payout->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if(isset($payout->approved_at) && $payout->approved_at)
                            <div>
                                <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Approved Date</p>
                                <p style="margin: 0;">{{ $payout->approved_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                    @if($payout->approved_by)
                        <div>
                            <p class="label" style="margin: 0 0 0.25rem; color: #5a5661; font-size: 0.875rem;">Approved By</p>
                            <p style="margin: 0; font-weight: 600;">{{ $payout->approver->name ?? 'N/A' }}</p>
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
                            @if($payout->status == 'PENDING')
                                <span class="pill" style="background: #FFF3E0; color: #E65100; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">⏳ Pending</span>
                            @elseif($payout->status == 'APPROVED')
                                <span class="pill" style="background: #E3F2FD; color: #1565C0; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">✓ Approved</span>
                            @elseif($payout->status == 'COMPLETED')
                                <span class="pill" style="background: #E8F5E9; color: #2E7D32; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">✓ Completed</span>
                            @else
                                <span class="pill" style="background: #FFEBEE; color: #C62828; font-size: 1.125rem; padding: 0.75rem 1.5rem; display: inline-block;">⚠️ Disputed</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($payout->status == 'PENDING')
                    <div class="card" style="margin-top: 1.5rem;">
                        <div class="card-head">
                            <h3>Actions</h3>
                        </div>
                        <div style="display: grid; gap: 0.75rem;">
                            <form action="{{ route('admin.payouts.approve', $payout) }}" method="POST">
                                @csrf
                                <button type="submit" class="pill" style="background: #2E7D32; color: white; border: none; width: 100%; justify-content: center; cursor: pointer;" onclick="return confirm('Approve this payout?')">✓ Approve Payout</button>
                            </form>
                            <form action="{{ route('admin.payouts.disputed', $payout) }}" method="POST">
                                @csrf
                                <button type="submit" class="pill" style="background: #C62828; color: white; border: none; width: 100%; justify-content: center; cursor: pointer;" onclick="return confirm('Mark this payout as disputed?')">⚠️ Mark Disputed</button>
                            </form>
                        </div>
                    </div>
                @endif

                @if($payout->status == 'APPROVED')
                    <div class="card" style="margin-top: 1.5rem;">
                        <div class="card-head">
                            <h3>Mark as Completed</h3>
                        </div>
                        <form action="{{ route('admin.payouts.completed', $payout) }}" method="POST">
                            @csrf
                            <div style="margin-bottom: 1rem;">
                                <label class="label">M-PESA Reference (Optional)</label>
                                <input type="text" name="mpesa_ref" class="form-control" placeholder="Enter M-PESA ref...">
                            </div>
                            <button type="submit" class="pill" style="background: var(--brand-primary, #652482); color: white; border: none; width: 100%; justify-content: center; cursor: pointer;">Mark Completed</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Bookings -->
        @if(isset($payout->booking_id) && $payout->booking_id)
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
                        @if($payout->booking)
                            <tr>
                                <td><code>#{{ $payout->booking->id }}</code></td>
                                <td>{{ $payout->booking->property->name ?? 'N/A' }}</td>
                                <td>{{ $payout->booking->guest->name ?? 'N/A' }}</td>
                                <td>{{ $payout->booking->check_in_date ? \Carbon\Carbon::parse($payout->booking->check_in_date)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $payout->booking->check_out_date ? \Carbon\Carbon::parse($payout->booking->check_out_date)->format('M d, Y') : 'N/A' }}</td>
                                <td><strong>{{ number_format($payout->booking->total_amount) }} KES</strong></td>
                                <td><span class="pill light">{{ $payout->booking->status }}</span></td>
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
        @endif
    </div>
@endsection
