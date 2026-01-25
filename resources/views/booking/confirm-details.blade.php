@extends('frontend.layouts.app')

@section('title', 'Confirm Booking Details')

@section('content')
<!-- Booking Confirmation Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <h1 class="h3 mb-2">Confirm Your Booking</h1>
                        <p class="text-muted">Please enter your details and confirm your reservation.</p>
                    </div>
                </div>

                <!-- Booking Details Display -->
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-calendar me-2 text-primary"></i>Your Stay</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="text-muted d-block mb-2">Check-In</small>
                                <p class="mb-0 fw-bold" id="display-checkin"></p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block mb-2">Check-Out</small>
                                <p class="mb-0 fw-bold" id="display-checkout"></p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block mb-2">Rooms</small>
                                <p class="mb-0 fw-bold" id="display-rooms"></p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block mb-2">Guests</small>
                                <p class="mb-0 fw-bold" id="display-guests"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Form with @csrf -->
                <form action="{{ route('booking.create') }}" method="POST" id="booking-form">
                    @csrf

                    <!-- Guest Information Section -->
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Guest Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="message" class="form-label">Special Requests</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                                    @error('message')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields for Booking Data -->
                    <input type="hidden" id="checkin" name="checkin">
                    <input type="hidden" id="checkout" name="checkout">
                    <input type="hidden" id="adult" name="adult">
                    <input type="hidden" id="children" name="children">
                    <input type="hidden" id="room_count" name="room_count">
                    <input type="hidden" id="room_type" name="room_type">

                    <!-- Info Alert -->
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Next Step:</strong> After confirming, you'll proceed to payment where you can choose to pay via M-PESA STK Push or Paybill.
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" onclick="editReservation()">
                            <i class="fas fa-edit me-2"></i>Edit Reservation
                        </button>
                        <button type="submit" class="btn-main">
                            <i class="fas fa-check me-2"></i>Proceed to Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get booking data from sessionStorage
    const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));
    
    if (!bookingData) {
        alert('No booking data found. Please start a new reservation.');
        window.location.href = '/';
        return;
    }
    
    // Display booking details
    document.getElementById('display-checkin').textContent = formatDisplayDate(bookingData.checkin);
    document.getElementById('display-checkout').textContent = formatDisplayDate(bookingData.checkout);
    document.getElementById('display-rooms').textContent = bookingData.room_count;
    document.getElementById('display-guests').textContent = bookingData.adult;
    
    // Populate hidden form fields
    document.getElementById('checkin').value = bookingData.checkin;
    document.getElementById('checkout').value = bookingData.checkout;
    document.getElementById('adult').value = bookingData.adult;
    document.getElementById('children').value = bookingData.children;
    document.getElementById('room_count').value = bookingData.room_count;
    document.getElementById('room_type').value = bookingData.room_type;
    
    // Pre-fill guest info if available
    if (bookingData.name) document.getElementById('name').value = bookingData.name;
    if (bookingData.email) document.getElementById('email').value = bookingData.email;
    if (bookingData.phone) document.getElementById('phone').value = bookingData.phone;
    if (bookingData.message) document.getElementById('message').value = bookingData.message;
});

function formatDisplayDate(dateStr) {
    // Convert "1/25/2026" to "Jan 25, 2026"
    const [month, day, year] = dateStr.split('/');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    return months[parseInt(month) - 1] + ' ' + parseInt(day) + ', ' + year;
}

function editReservation() {
    // Clear sessionStorage and return to home
    sessionStorage.removeItem('bookingData');
    window.location.href = '/';
}
</script>

@endsection
