<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Booking;

class BookingSubmissionController extends Controller
{
    /**
     * Handle reservation form submission from frontend
     * Stores data in session and redirects to preview (doesn't create booking yet)
     */
    public function submitReservation(Request $request)
    {
        // Log the incoming request for debugging
        \Log::info('Booking submission request:', [
            'all' => $request->all(),
            'checkin' => $request->input('checkin'),
            'checkout' => $request->input('checkout'),
            'method' => $request->method(),
            'path' => $request->path(),
        ]);

        // Validate the incoming request
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email',
                'phone' => 'nullable|string|max:20',
                'message' => 'nullable|string|max:1000',
                'checkin' => 'required|date_format:n/j/Y',
                'checkout' => 'required|date_format:n/j/Y|after:checkin',
                'adult' => 'required|integer|min:1',
                'children' => 'required|integer|min:0',
                'room_count' => 'required|integer|min:1',
                'property_id' => 'required|integer|exists:properties,id',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            throw $e;
        }

        try {
            // Get property name for display
            $property = Property::find($validated['property_id']);
            
            // Store all form data in session for later booking creation
            session(['pending_booking_data' => [
                'name' => $validated['name'] ?? '',
                'email' => $validated['email'] ?? '',
                'phone' => $validated['phone'] ?? '',
                'message' => $validated['message'] ?? '',
                'checkin' => $validated['checkin'],
                'checkout' => $validated['checkout'],
                'adult' => $validated['adult'],
                'children' => $validated['children'],
                'room_count' => $validated['room_count'],
                'property_id' => $validated['property_id'],
                'property_name' => $property->name ?? '',
            ]]);

            // Redirect to summary preview (without booking ref since no booking exists yet)
            return redirect()->route('booking.preview')->with('success', 'Please review your booking details.');
        } catch (\Exception $e) {
            \Log::error('Booking submission failed', [
                'error' => $e->getMessage(),
                'email' => $validated['email'] ?? 'unknown',
            ]);

            return back()->withErrors(['error' => 'Failed to process reservation. ' . $e->getMessage()]);
        }
    }

    /**
     * Generate a unique booking reference
     */
    private function generateBookingReference(): string
    {
        do {
            $ref = 'BOOK-' . strtoupper(Str::random(8));
        } while (Booking::where('booking_ref', $ref)->exists());

        return $ref;
    }
}
