@extends('frontend.layouts.app')

@section('title', 'Confirm Your Reservation')

@section('content')
<!-- Confirmation Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <h1 class="h3 mb-2">Confirm Your Reservation</h1>
                        <p class="text-muted">Please review your stay details and enter your contact information below.</p>
                    </div>
                </div>

                <!-- Stay Details Display -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-calendar-check me-2 text-primary"></i>Your Stay Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-3">
                                <small class="text-muted d-block fw-600 mb-2">CHECK-IN</small>
                                <p class="mb-0 fs-16" id="display-checkin">-</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block fw-600 mb-2">CHECK-OUT</small>
                                <p class="mb-0 fs-16" id="display-checkout">-</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block fw-600 mb-2">ROOMS</small>
                                <p class="mb-0 fs-16" id="display-rooms">-</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block fw-600 mb-2">GUESTS</small>
                                <p class="mb-0 fs-16" id="display-guests">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form with @csrf Token -->
                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf
                    
                    <!-- Hidden Fields - Contains data from query parameters -->
                    <input type="hidden" id="hidden-checkin" name="checkin">
                    <input type="hidden" id="hidden-checkout" name="checkout">
                    <input type="hidden" id="hidden-rooms" name="rooms">
                    <input type="hidden" id="hidden-guests" name="guests">

                    <!-- Guest Information Section -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Guest Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-500">Full Name *</label>
                                    <input 
                                        type="text" 
                                        id="name" 
                                        name="name" 
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-500">Email *</label>
                                    <input 
                                        type="email" 
                                        id="email" 
                                        name="email" 
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                        required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label fw-500">Phone Number *</label>
                                    <input 
                                        type="tel" 
                                        id="phone" 
                                        name="phone" 
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}"
                                        required>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="special_requests" class="form-label fw-500">Special Requests (Optional)</label>
                                    <textarea 
                                        id="special_requests" 
                                        name="special_requests" 
                                        class="form-control @error('special_requests') is-invalid @enderror"
                                        rows="3">{{ old('special_requests') }}</textarea>
                                    @error('special_requests')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Secure Booking:</strong> Your information is protected. After confirming, you'll proceed to payment.
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center mb-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="goBack()">
                            <i class="fas fa-arrow-left me-2"></i>Back to Edit
                        </button>
                        <button type="submit" class="btn-main">
                            <i class="fas fa-check me-2"></i>Confirm & Proceed to Payment
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Extract query parameters
    const params = new URLSearchParams(window.location.search);
    
    const checkin = params.get('checkin');
    const checkout = params.get('checkout');
    const rooms = params.get('rooms');
    const guests = params.get('guests');

    // Validate that we have all required parameters
    if (!checkin || !checkout || !rooms || !guests) {
        alert('Invalid booking data. Please start over.');
        window.location.href = '/reservation';
        return;
    }

    // Display values on page
    document.getElementById('display-checkin').textContent = checkin;
    document.getElementById('display-checkout').textContent = checkout;
    document.getElementById('display-rooms').textContent = rooms;
    document.getElementById('display-guests').textContent = guests;

    // Populate hidden form fields
    document.getElementById('hidden-checkin').value = checkin;
    document.getElementById('hidden-checkout').value = checkout;
    document.getElementById('hidden-rooms').value = rooms;
    document.getElementById('hidden-guests').value = guests;
});

function goBack() {
    window.location.href = '/reservation';
}
</script>

@endsection
