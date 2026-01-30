@extends('layouts.velzon.app')

@section('title', 'M-Pesa Payment Verification')
@section('page-title', 'M-Pesa Payment Verification')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pending Payments</h5>
                <p class="text-muted">Review and verify M-Pesa payments submitted by guests</p>
            </div>
        </div>

        <!-- Submissions Table -->
        @if ($submissions->count() > 0)
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Booking Ref</th>
                                <th>Guest Name</th>
                                <th>M-Pesa Code</th>
                                <th>Amount</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $submission)
                                <tr>
                                    <td><strong>{{ $submission->booking->booking_ref }}</strong></td>
                                    <td>{{ $submission->booking->guest->full_name }}</td>
                                    <td>
                                        <code>{{ $submission->mpesa_code }}</code>
                                    </td>
                                    <td>KES {{ number_format($submission->amount, 2) }}</td>
                                    <td>{{ $submission->phone }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $submission->status }}</span>
                                        <br>
                                        <small class="text-muted">{{ $submission->payment_status }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $submission->created_at->format('M d, Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.mpesa-verification.show', $submission->id) }}" 
                                           class="btn btn-sm btn-primary">Review</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <p class="text-muted mb-0">No pending M-Pesa payments to verify</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
