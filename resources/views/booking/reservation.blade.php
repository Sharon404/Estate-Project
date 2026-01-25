@extends('frontend.layouts.app')

@section('title', 'Make a Reservation')

@section('content')
<!-- Reservation Form Section -->
<section aria-label="reservation">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bg-white p-40 rounded-1">
                    <h2 class="mb-4">Check Availability & Make a Reservation</h2>
                    <p class="text-muted mb-4">Fill in your preferred dates and room requirements below. You'll review all details on the next step.</p>

                    <!-- Reservation Form - NO ACTION, NO POST METHOD - JavaScript handles validation and redirect -->
                    <form id="reservationForm" onsubmit="return false;">
                        <div class="row g-4 align-items-end">
                            
                            <!-- Check-In Date -->
                            <div class="col-md-1-5">
                                <label for="checkin" class="form-label fw-500 mb-2">Check In *</label>
                                <input 
                                    type="date" 
                                    id="checkin" 
                                    name="checkin"
                                    class="form-control" 
                                    required>
                            </div>

                            <!-- Check-Out Date -->
                            <div class="col-md-1-5">
                                <label for="checkout" class="form-label fw-500 mb-2">Check Out *</label>
                                <input 
                                    type="date" 
                                    id="checkout" 
                                    name="checkout"
                                    class="form-control" 
                                    required>
                            </div>

                            <!-- Number of Rooms -->
                            <div class="col-md-1-5">
                                <label for="rooms" class="form-label fw-500 mb-2">Rooms *</label>
                                <select id="rooms" name="rooms" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <!-- Number of Adults -->
                            <div class="col-md-1-5">
                                <label for="guests" class="form-label fw-500 mb-2">Adults *</label>
                                <select id="guests" name="guests" class="form-control" required>
                                    <option value="">Select...</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>

                            <!-- Number of Children -->
                            <div class="col-md-1-5">
                                <label for="children" class="form-label fw-500 mb-2">Children</label>
                                <select id="children" name="children" class="form-control">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>

                        </div>

                        <div class="row g-4 mt-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-500 mb-2">Full Name *</label>
                                <input type="text" id="full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-500 mb-2">Email *</label>
                                <input type="email" id="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-500 mb-2">Phone *</label>
                                <input type="tel" id="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="notes" class="form-label fw-500 mb-2">Additional Notes</label>
                                <textarea id="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="row g-4 mt-4">
                            <div class="col-md-3">
                                <button type="button" class="btn-main w-100" onclick="goToConfirm()">Review &amp; Confirm</button>
                            </div>
                        </div>
                    </form>

                    <!-- Info Message -->
                    <div class="alert alert-info mt-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>How it works:</strong> Select your dates and preferences above, then review and confirm your details on the next page.
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
function validateAndRedirect(event) {
    event.preventDefault();
    
    // Get form values
    const checkin = document.getElementById('checkin').value.trim();
    const checkout = document.getElementById('checkout').value.trim();
    const rooms = document.getElementById('rooms').value;
    const guests = document.getElementById('guests').value;

    // Validate dates
    if (!checkin || !checkout) {
        alert('Please select both check-in and check-out dates');
        return false;
    }

    // Validate that checkout is after checkin
    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    if (checkoutDate <= checkinDate) {
        alert('Check-out date must be after check-in date');
        return false;
    }

    // Validate rooms and guests
    if (!rooms || !guests) {
        alert('Please select number of rooms and guests');
        return false;
    }

    // Build query string with all parameters
    const params = new URLSearchParams();
    params.append('checkin', checkin);
    params.append('checkout', checkout);
    params.append('rooms', rooms);
    params.append('guests', guests);

    // Redirect to confirmation page with query parameters (NO POST)
    window.location.href = `/reservation/confirm?${params.toString()}`;
}
</script>

@endsection
