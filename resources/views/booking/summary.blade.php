@extends('frontend.layouts.app')

@section('title', 'Booking Summary - ' . $booking->booking_ref)

@section('content')
<!-- Booking Summary Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <h1 class="h3 mb-2">Review Your Booking</h1>
                        <p class="text-muted">Please review all your details below. You can edit or proceed to payment.</p>
                        <p class="mb-0"><strong>Booking Reference:</strong> <span class="text-primary fw-bold">{{ $booking->booking_ref }}</span></p>
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
                                <p class="mb-0 fw-bold">{{ $booking->guest->full_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Email</small>
                                <p class="mb-0 fw-bold">{{ $booking->guest->email }}</p>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <small class="text-muted d-block mb-1">Phone</small>
                                <p class="mb-0 fw-bold">{{ $booking->guest->phone_e164 }}</p>
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
                                <p class="mb-0 fw-bold">{{ $booking->check_in->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Check-Out</small>
                                <p class="mb-0 fw-bold">{{ $booking->check_out->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Duration</small>
                                <p class="mb-0 fw-bold">{{ $booking->nights }} Night{{ $booking->nights !== 1 ? 's' : '' }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block mb-1">Property</small>
                                <p class="mb-0 fw-bold">{{ $booking->property->name }}</p>
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
                                <p class="mb-0 fw-bold">{{ $booking->adults }}</p>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-muted d-block mb-1">Children</small>
                                <p class="mb-0 fw-bold">{{ $booking->children }}</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block mb-1">Rooms</small>
                                <p class="mb-0 fw-bold">{{ $booking->rooms }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Special Requests Card (if any) -->
                @if($booking->special_requests)
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-note-sticky me-2 text-primary"></i>Special Requests</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $booking->special_requests }}</p>
                    </div>
                </div>
                @endif

                <!-- Pricing Summary Card -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Price Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Nightly Rate</small>
                            </div>
                            <div class="col-md-6 text-end fw-bold">
                                {{ number_format($booking->nightly_rate, 2) }} {{ $booking->currency }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Nights × Rooms</small>
                            </div>
                            <div class="col-md-6 text-end fw-bold">
                                {{ $booking->nights }} × {{ $booking->rooms }} = {{ $booking->nights * $booking->rooms }}
                            </div>
                        </div>
                        <div class="row pb-3 border-bottom mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Subtotal (Accommodation)</small>
                            </div>
                            <div class="col-md-6 text-end fw-bold">
                                {{ number_format($booking->accommodation_subtotal, 2) }} {{ $booking->currency }}
                            </div>
                        </div>
                        @if($booking->addons_subtotal > 0)
                        <div class="row pb-3 border-bottom mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Add-ons</small>
                            </div>
                            <div class="col-md-6 text-end fw-bold">
                                {{ number_format($booking->addons_subtotal, 2) }} {{ $booking->currency }}
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-0">Total Amount Due</h5>
                                <small class="text-muted">Includes breakfast & hospitality</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <h4 class="mb-0 text-primary fw-bold">{{ number_format($booking->total_amount, 2) }} {{ $booking->currency }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <a href="{{ route('reservation') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-edit me-2"></i>
                            Edit Information
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('payment.show', ['booking' => $booking->id]) }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-credit-card me-2"></i>
                            Proceed to Payment
                        </a>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> By clicking "Proceed to Payment", you will be taken to the payment page where you can pay via M-PESA STK Push or Paybill.
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
