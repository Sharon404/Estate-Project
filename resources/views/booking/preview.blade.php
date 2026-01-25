@extends('frontend.layouts.app')

@section('title', 'Booking Preview')

@section('content')
<!-- Booking Preview Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <h1 class="h3 mb-2">Review Your Booking</h1>
                        <p class="text-muted">Please review all your details below. You can edit or proceed to confirm and pay.</p>
                    </div>
                </div>

                <!-- Guest Information Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Guest Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Full Name</small>
                                <p class="mb-0 fw-bold">{{ $data['name'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Email</small>
                                <p class="mb-0 fw-bold">{{ $data['email'] }}</p>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <small class="text-muted d-block mb-1">Phone</small>
                                <p class="mb-0 fw-bold">{{ $data['phone'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stay Details Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-calendar me-2 text-primary"></i>Stay Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Check-In</small>
                                <p class="mb-0 fw-bold">
                                    @php
                                        $checkIn = \Carbon\Carbon::createFromFormat('n/j/Y', $data['checkin']);
                                        echo $checkIn->format('M d, Y');
                                    @endphp
                                </p>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Check-Out</small>
                                <p class="mb-0 fw-bold">
                                    @php
                                        $checkOut = \Carbon\Carbon::createFromFormat('n/j/Y', $data['checkout']);
                                        echo $checkOut->format('M d, Y');
                                    @endphp
                                </p>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Duration</small>
                                <p class="mb-0 fw-bold">
                                    @php
                                        $nights = $checkIn->diffInDays($checkOut);
                                        echo $nights . ' Night' . ($nights !== 1 ? 's' : '');
                                    @endphp
                                </p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block mb-1">Room Type</small>
                                <p class="mb-0 fw-bold">{{ $data['room_type'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guests & Rooms Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Guests & Rooms</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Adults</small>
                                <p class="mb-0 fw-bold">{{ $data['adult'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Children</small>
                                <p class="mb-0 fw-bold">{{ $data['children'] }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block mb-1">Rooms</small>
                                <p class="mb-0 fw-bold">{{ $data['room_count'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special Requests Card (if any) -->
                @if($data['message'])
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-note-sticky me-2 text-primary"></i>Special Requests</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $data['message'] }}</p>
                    </div>
                </div>
                @endif

                <!-- Price Breakdown Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Price Breakdown</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $checkIn = \Carbon\Carbon::createFromFormat('n/j/Y', $data['checkin']);
                            $checkOut = \Carbon\Carbon::createFromFormat('n/j/Y', $data['checkout']);
                            $nights = $checkIn->diffInDays($checkOut);
                            $nightly_rate = 25000; // KES 25,000 per night
                            $subtotal = $nightly_rate * $nights * $data['room_count'];
                            $total = $subtotal;
                        @endphp
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-md-8">
                                <p class="mb-0">KES {{ number_format($nightly_rate) }} × {{ $nights }} night{{ $nights !== 1 ? 's' : '' }} × {{ $data['room_count'] }} room{{ $data['room_count'] > 1 ? 's' : '' }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <p class="mb-0 fw-bold">KES {{ number_format($subtotal) }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <p class="mb-0 fs-5 fw-bold">Total Amount Due</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <p class="mb-0 fs-5 fw-bold text-primary">KES {{ number_format($total) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Next Steps:</strong> After confirming, you'll be taken to the payment page where you can choose to pay via M-PESA STK Push or Paybill.
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" onclick="editReservation()">
                        <i class="fas fa-edit me-2"></i>Edit Information
                    </button>
                    <form action="{{ route('booking.confirm') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-main">
                            <i class="fas fa-check me-2"></i>Proceed to Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function editReservation() {
    // Go back to home to edit
    window.location.href = '/';
}
</script>

@endsection
