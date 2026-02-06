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
                            <div class="col-md-4">
                                <small class="text-muted d-block fw-600 mb-2">CHECK-IN</small>
                                <p class="mb-0 fs-16" id="display-checkin">-</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block fw-600 mb-2">CHECK-OUT</small>
                                <p class="mb-0 fs-16" id="display-checkout">-</p>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block fw-600 mb-2">GUESTS</small>
                                <p class="mb-0 fs-16" id="display-guests">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Read-Only Details + Single POST Form -->
                <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                    @csrf

                    <!-- Hidden Fields (all params) -->
                    <input type="hidden" id="hidden-checkin" name="checkin">
                    <input type="hidden" id="hidden-checkout" name="checkout">
                    <input type="hidden" id="hidden-adults" name="adults">
                    <input type="hidden" id="hidden-children" name="children">
                    <input type="hidden" id="hidden-full-name" name="full_name">
                    <input type="hidden" id="hidden-email" name="email">
                    <input type="hidden" id="hidden-phone" name="phone">
                    <input type="hidden" id="hidden-notes" name="notes">

                    <!-- Guest Information (read-only) -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Guest Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block fw-600 mb-2">FULL NAME</small>
                                    <p class="mb-0 fs-16" id="display-name">-</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block fw-600 mb-2">EMAIL</small>
                                    <p class="mb-0 fs-16" id="display-email">-</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block fw-600 mb-2">PHONE</small>
                                    <p class="mb-0 fs-16" id="display-phone">-</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block fw-600 mb-2">ADDITIONAL NOTES</small>
                                    <p class="mb-0 fs-16" id="display-notes">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-lock me-2"></i>
                        <strong>Secure Booking:</strong> After confirmation you'll be taken to payment.
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center mb-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="editReservation()">
                            <i class="fas fa-arrow-left me-2"></i>Edit Details
                        </button>
                        <button type="submit" class="btn-main">
                            <i class="fas fa-check me-2"></i>Proceed to Pay
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);

    const checkin = params.get('checkin');
    const checkout = params.get('checkout');
    const adults = parseInt(params.get('adults') || '0', 10);
    const children = parseInt(params.get('children') || '0', 10);
    const fullName = params.get('full_name');
    const email = params.get('email');
    const phone = params.get('phone');
    const notes = params.get('notes') || '';
    const propertyId = params.get('property_id');
    const propertyName = params.get('property_name');
    const nightlyRate = params.get('nightly_rate');
    const currency = params.get('currency');
    const totalPrice = params.get('total_price');

    // Validate presence (no rooms needed for entire home)
    if (!checkin || !checkout || !adults || !fullName || !email || !phone || !propertyId) {
        alert('Missing reservation details. Please start over.');
        window.location.href = '/reservation';
        return;
    }

    // Display property details
    if (propertyName) document.getElementById('display-property').textContent = propertyName;
    if (nightlyRate && currency) document.getElementById('nightly_rate_display').textContent = currency + ' ' + parseFloat(nightlyRate).toLocaleString();
    if (totalPrice && currency) document.getElementById('total_price_display').textContent = currency + ' ' + parseFloat(totalPrice).toLocaleString();

    // Display booking dates and guests
    document.getElementById('display-checkin').textContent = checkin;
    document.getElementById('display-checkout').textContent = checkout;
    const guestsText = children > 0 ? `${adults} adult(s), ${children} child(ren)` : `${adults} adult(s)`;
    document.getElementById('display-guests').textContent = guestsText;
    
    // Display guest information
    document.getElementById('display-name').textContent = fullName;
    document.getElementById('display-email').textContent = email;
    document.getElementById('display-phone').textContent = phone;
    document.getElementById('display-notes').textContent = notes || '-';

    // Hidden inputs for POST (all booking data)
    document.getElementById('hidden-property-id').value = propertyId;
    document.getElementById('hidden-property-name').value = propertyName;
    document.getElementById('hidden-nightly-rate').value = nightlyRate;
    document.getElementById('hidden-currency').value = currency;
    document.getElementById('hidden-total-price').value = totalPrice;
    document.getElementById('hidden-checkin').value = checkin;
    document.getElementById('hidden-checkout').value = checkout;
    document.getElementById('hidden-adults').value = adults;
    document.getElementById('hidden-children').value = children;
    document.getElementById('hidden-full-name').value = fullName;
    document.getElementById('hidden-email').value = email;
    document.getElementById('hidden-phone').value = phone;
    document.getElementById('hidden-notes').value = notes;
});

function editReservation() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = `/reservation?${params.toString()}`;
}
</script>

@endsection
