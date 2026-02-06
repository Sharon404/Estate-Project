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

                    <!-- Reservation Form -->
                    <form id="reservationForm" onsubmit="return false;">
                        <div class="row g-4 align-items-end">
                            
                            <!-- Property Selector -->
                            <div class="col-md-2">
                                <label for="property_selector" class="form-label fw-500 mb-2">Select Property *</label>
                                <select id="property_selector" name="property_id" class="form-control" required onchange="updatePropertyRate()">
                                    <option value="">Choose a property...</option>
                                    @forelse($availableProperties as $prop)
                                        <option value="{{ $prop->id }}" data-rate="{{ $prop->nightly_rate }}" data-currency="{{ $prop->currency }}" data-name="{{ $prop->name }}">{{ Str::limit($prop->name, 20) }} - {{ $prop->currency }} {{ number_format($prop->nightly_rate, 0) }}/nt</option>
                                    @empty
                                        <option value="" disabled>No properties available</option>
                                    @endforelse
                                </select>
                            </div>
                            
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
                            <div class="col-md-1-5">
                                <label for="checkout" class="form-label fw-500 mb-2">Check Out *</label>
                                <input 
                                    type="date" 
                                    id="checkout" 
                                    name="checkout"
                                    class="form-control" 
                                    required
                                    onchange="calculateTotal()">
                            </div>

                            <!-- Number of Rooms -->
                            <div class="col-md-1-5">
                                <label for="rooms" class="form-label fw-500 mb-2">Rooms *</label>
                                <select id="rooms" name="rooms" class="form-control" required onchange="calculateTotal()">
                                    <option value="">Select...</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <!-- Nightly Rate Display -->
                            <div class="col-md-1-5">
                                <label class="form-label fw-500 mb-2">Nightly Rate</label>
                                <div class="form-control-plaintext fs-16 fw-bold text-primary">
                                    <span id="nightly_rate_display">-</span>
                                </div>
                            </div>

                            <!-- Total Price Display -->
                            <div class="col-md-1-5">
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
// Update nightly rate display when property is selected
function updatePropertyRate() {
    const selector = document.getElementById('property_selector');
    const selectedOption = selector.options[selector.selectedIndex];
    
    if (!selectedOption.value) {
        document.getElementById('nightly_rate_display').textContent = '-';
        document.getElementById('total_price_display').textContent = '-';
        return;
    }
    
    const rate = parseFloat(selectedOption.getAttribute('data-rate'));
    const currency = selectedOption.getAttribute('data-currency');
    
    document.getElementById('nightly_rate_display').textContent = currency + ' ' + rate.toLocaleString();
    calculateTotal();
}

// Calculate and display total price
function calculateTotal() {
    const checkin = document.getElementById('checkin').value;
    const checkout = document.getElementById('checkout').value;
    const rooms = parseInt(document.getElementById('rooms').value) || 0;
    
    if (!checkin || !checkout || !rooms) {
        document.getElementById('total_price_display').textContent = '-';
        return;
    }
    
    const selector = document.getElementById('property_selector');
    const selectedOption = selector.options[selector.selectedIndex];
    
    if (!selectedOption.value) {
        document.getElementById('total_price_display').textContent = '-';
        return;
    }
    
    const rate = parseFloat(selectedOption.getAttribute('data-rate'));
    const currency = selectedOption.getAttribute('data-currency');
    
    const checkinDate = new Date(checkin);
    const checkoutDate = new Date(checkout);
    const nights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
    
    if (nights <= 0) {
        document.getElementById('total_price_display').textContent = '-';
        return;
    }
    
    const total = rate * nights * rooms;
    document.getElementById('total_price_display').textContent = currency + ' ' + total.toLocaleString();
}

function goToConfirm() {
    // Collect values
    const propertyId = document.getElementById('property_selector').value;
    const propertyName = document.getElementById('property_selector').options[document.getElementById('property_selector').selectedIndex].getAttribute('data-name');
    const nightly_rate = document.getElementById('property_selector').options[document.getElementById('property_selector').selectedIndex].getAttribute('data-rate');
    const currency = document.getElementById('property_selector').options[document.getElementById('property_selector').selectedIndex].getAttribute('data-currency');
    
    const checkin = document.getElementById('checkin').value.trim();
    const checkout = document.getElementById('checkout').value.trim();
    const rooms = document.getElementById('rooms').value;
    const adults = document.getElementById('guests').value;
    const children = document.getElementById('children').value;
    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const notes = document.getElementById('notes').value.trim();

    // Validation
    if (!propertyId) {
        alert('Please select a property');
        return;
    }

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

    if (!rooms || !adults) {
        alert('Please select rooms and adults');
        return;
    }

    if (!fullName || !email || !phone) {
        alert('Please provide your full name, email, and phone');
        return;
    }

    // Calculate total
    const nights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
    const total_price = parseFloat(nightly_rate) * nights * parseInt(rooms);

    // Build query parameters
    const params = new URLSearchParams();
    params.append('checkin', checkin);
    params.append('checkout', checkout);
    params.append('rooms', rooms);
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
