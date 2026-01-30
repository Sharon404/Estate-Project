@extends('layouts.velzon.app')

@section('title', 'Verify Payment - M-Pesa')
@section('page-title', 'Verify M-Pesa Payment')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Booking Details -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Booking Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Booking Reference:</strong><br>
                            <code class="bg-light p-2">{{ $submission->booking->booking_ref }}</code>
                        </p>
                        <p class="mb-2">
                            <strong>Guest Name:</strong><br>
                            {{ $submission->booking->guest->full_name }}
                        </p>
                        <p class="mb-2">
                            <strong>Guest Email:</strong><br>
                            {{ $submission->booking->guest->email }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Check-in:</strong><br>
                            {{ $submission->booking->check_in->format('M d, Y') }}
                        </p>
                        <p class="mb-2">
                            <strong>Check-out:</strong><br>
                            {{ $submission->booking->check_out->format('M d, Y') }}
                        </p>
                        <p class="mb-2">
                            <strong>Amount Due:</strong><br>
                            <strong class="text-danger">KES {{ number_format($submission->booking->amount_due, 2) }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- M-Pesa Code Submission -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">M-Pesa Code Submission</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong>M-Pesa Code:</strong><br>
                            <code class="bg-light p-2 d-block">{{ $submission->mpesa_code }}</code>
                        </p>
                        <p class="mb-3">
                            <strong>Phone Number:</strong><br>
                            {{ $submission->phone ?: 'Not provided' }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-3">
                            <strong>Submitted Amount:</strong><br>
                            <strong class="fs-5">KES {{ number_format($submission->amount, 2) }}</strong>
                        </p>
                        <p class="mb-3">
                            <strong>Submitted At:</strong><br>
                            {{ $submission->created_at->format('M d, Y H:i:s') }}
                        </p>
                    </div>
                </div>
                @if ($submission->admin_notes)
                    <div class="alert alert-info mb-0">
                        <strong>Guest Notes:</strong> {{ $submission->admin_notes }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Verification Form -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Verify Payment</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.mpesa-verification.verify', $submission->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="payment_status" class="form-label">
                            <strong>Payment Status</strong>
                            <span class="text-danger">*</span>
                        </label>
                        <select id="payment_status" name="payment_status" class="form-select" required>
                            <option value="">-- Select Status --</option>
                            <option value="CONFIRMED">✓ CONFIRMED - Payment is valid and verified</option>
                            <option value="NOT_FOUND">✗ NOT FOUND - M-Pesa code not found in system</option>
                            <option value="MISMATCH">⚠ MISMATCH - Code exists but amount/details don't match</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Admin Notes (Optional)</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3" 
                                  placeholder="Enter any notes about the verification..."></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="ri-check-line"></i> Verify Payment
                        </button>
                        <a href="{{ route('admin.mpesa-verification.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar: Payment History -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Payment History</h5>
            </div>
            <div class="card-body">
                <p>
                    <strong>Amount Paid:</strong><br>
                    <span class="text-success">KES {{ number_format($submission->booking->amount_paid, 2) }}</span>
                </p>
                <p>
                    <strong>Amount Due:</strong><br>
                    <span class="text-danger">KES {{ number_format($submission->booking->amount_due, 2) }}</span>
                </p>
                <p>
                    <strong>Total Amount:</strong><br>
                    <strong>KES {{ number_format($submission->booking->total_amount, 2) }}</strong>
                </p>
                <hr>
                <p class="text-muted mb-0">
                    <small>Booking Status: 
                        <span class="badge bg-warning">{{ $submission->booking->status }}</span>
                    </small>
                </p>
            </div>
        </div>

        <!-- Recent Submissions -->
        <div class="card mt-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Recent Submissions</h5>
            </div>
            <div class="card-body">
                @php
                    $recentSubmissions = \App\Models\MpesaManualSubmission::where('booking_id', $submission->booking_id)
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                @forelse ($recentSubmissions as $recent)
                    <div class="mb-3 pb-3 border-bottom">
                        <p class="mb-1">
                            <code>{{ $recent->mpesa_code }}</code>
                        </p>
                        <small class="text-muted">
                            KES {{ number_format($recent->amount, 2) }} • 
                            <span class="badge bg-{{ $recent->status === 'VERIFIED' ? 'success' : 'warning' }}">
                                {{ $recent->status }}
                            </span>
                        </small>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No other submissions</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // Auto-fill notes based on payment status
    document.getElementById('payment_status').addEventListener('change', function() {
        const notes = document.getElementById('notes');
        const status = this.value;

        if (status === 'CONFIRMED') {
            notes.value = 'Payment verified against M-Pesa records. Amount and details match.';
        } else if (status === 'NOT_FOUND') {
            notes.value = 'M-Pesa transaction code not found in system records.';
        } else if (status === 'MISMATCH') {
            notes.value = 'M-Pesa code exists but amount or details do not match submitted information.';
        }
    });
</script>
@endsection
@endsection
