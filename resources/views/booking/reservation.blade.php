@extends('frontend.layouts.app')

@section('title', 'Make a Reservation')

@section('content')
<!-- Reservation Form Section -->
<section aria-label="reservation">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bg-white p-40 rounded-1">
                    <!-- Selected Property Display -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-home me-3 fs-4"></i>
                        <div>
                            <h5 class="mb-1">{{ $property->name }}</h5>
                            <p class="mb-0 small">{{ $property->currency }} {{ number_format($property->nightly_rate, 0) }} per night | Entire Home</p>
                        </div>
                    </div>
                    
                    <h2 class="mb-4">Check Availability & Make a Reservation</h2>
                    <p class="text-muted mb-4">Select your dates below. You'll review all details on the next step.</p>

                    <!-- Reservation Form -->
                    <form id="reservationForm" onsubmit="return false;">
                        <!-- Hidden Property ID -->
                        <input type="hidden" id="property_id" name="property_id" value="{{ $property->id }}">
                        <input type="hidden" id="property_name" name="property_name" value="{{ $property->name }}">
                        <input type="hidden" id="nightly_rate" name="nightly_rate" value="{{ $property->nightly_rate }}">
                        <input type="hidden" id="currency" name="currency" value="{{ $property->currency }}">
                        
                        <div class="row g-4 align-items-end">
                            
                            <!-- Check-In Date -->
                            <div class="col-md-1-5">
                                <label for="checkin" class="form-label fw-500 mb-2">Check In *</label>
                                <input 
                                    type="date" 
                                    id="checkin" 
                                    name="checkin"
                                    class="form-control" 
                                    required
                                    onchange="calculateTotal()">
                            </div>

                            <!-- Check-Out Date -->
                            <div class="col-md-3">
                                <label for="checkout" class="form-label fw-500 mb-2">Check Out *</label>
                                <input 
                                    type="date" 
                                    id="checkout" 
                                    name="checkout"
                                    class="form-control" 
                                    required
                                    onchange="calculateTotal()">
                            </div>

                            <!-- Total Price Display -->
                            <div class="col-md-3">
                                <label class="form-label fw-500 mb-2">Total</label>
                                <div class="form-control-plaintext fs-18 fw-bold text-success">
                                    <span id="total_price_display">-</span>
                                </div>
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
// Fetch and disable booked dates when page loads
let bookedDates = [];

document.addEventListener('DOMContentLoaded', function() {
    const propertyId = document.getElementById('property_id').value;
    const today = new Date().toISOString().split('T')[0];
    
    // Set minimum date to today
    document.getElementById('checkin').setAttribute('min', today);
    document.getElementById('checkout').setAttribute('min', today);
    
    // Fetch booked dates from server
    fetch(`/api/property/${propertyId}/booked-dates`)
        .then(response => response.json())
        .then(data => {
            bookedDates = data.booked_dates || [];
            console.log('Loaded booked dates:', bookedDates.length);
        })
        .catch(error => {
            console.error('Error fetching booked dates:', error);
        });
});

// Validate selected dates aren't booked
function validateDates() {
    const checkin = document.getElementById('checkin').value;
    const checkout = document.getElementById('checkout').value;
    
    if (!checkin || !checkout) return true;
    
    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    
    // Check if any date in the range is already booked
    for (let d = new Date(checkinDate); d < checkoutDate; d.setDate(d.getDate() + 1)) {
        const dateStr = d.toISOString().split('T')[0];
        if (bookedDates.includes(dateStr)) {
            alert(`Sorry, this property is not available for the selected dates. ${dateStr} is already booked.`);
            return false;
        }
    }
    
    return true;
}

// Calculate and display total price (entire home = no room count needed)
function calculateTotal() {
    const checkin = document.getElementById('checkin').value;
    const checkout = document.getElementById('checkout').value;
    
    if (!checkin || !checkout) {
        document.getElementById('total_price_display').textContent = '-';
        return;
    }
    
    // Validate dates aren't booked
    if (!validateDates()) {
        document.getElementById('total_price_display').textContent = '-';
        document.getElementById('checkin').value = '';
        document.getElementById('checkout').value = '';
        return;
    }
    
    // Get property data from hidden fields
    const rate = parseFloat(document.getElementById('nightly_rate').value);
    const currency = document.getElementById('currency').value;
    
    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    const nights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
    
    if (nights <= 0) {
        document.getElementById('total_price_display').textContent = '-';
        return;
    }
    
    // Entire home booking - no room multiplier
    const total = rate * nights;
    document.getElementById('total_price_display').textContent = currency + ' ' + total.toLocaleString();
}

function goToConfirm() {
    // Collect values from hidden fields and form inputs
    const propertyId = document.getElementById('property_id').value;
    const propertyName = document.getElementById('property_name').value;
    const nightly_rate = document.getElementById('nightly_rate').value;
    const currency = document.getElementById('currency').value;
    
    const checkin = document.getElementById('checkin').value.trim();
    const checkout = document.getElementById('checkout').value.trim();
    const adults = document.getElementById('guests').value;
    const children = document.getElementById('children').value;
    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const notes = document.getElementById('notes').value.trim();

    // Validation
    if (!checkin || !checkout) {
        alert('Please select both check-in and check-out dates');
        return;
    }

    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    if (checkoutDate <= checkinDate) {
        alert('Check-out date must be after check-in date');
        return;
    }

    if (!adults) {
        alert('Please select number of adults');
        return;
    }

    if (!fullName || !email || !phone) {
        alert('Please provide your full name, email, and phone');
        return;
    }

    // Calculate total (entire home - no room count)
    const nights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
    const total_price = parseFloat(nightly_rate) * nights;

    // Build query parameters
    const params = new URLSearchParams();
    params.append('checkin', checkin);
    params.append('checkout', checkout);
    params.append('adults', adults);
    params.append('children', children || '0');
    params.append('full_name', fullName);
    params.append('email', email);
    params.append('phone', phone);
    params.append('property_id', propertyId);
    params.append('property_name', propertyName);
    params.append('nightly_rate', nightly_rate);
    params.append('currency', currency);
    params.append('total_price', total_price);
    if (notes) params.append('notes', notes);

    // Redirect to confirmation page with all data
    window.location.href = `/reservation/confirm?${params.toString()}`;
}
</script>

@endsection
