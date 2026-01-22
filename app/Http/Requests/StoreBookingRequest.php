<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Guest information
            'guest.full_name' => 'required|string|max:255',
            'guest.email' => 'required|email|max:255',
            'guest.phone_e164' => 'required|string|max:20',

            // Booking details
            'property_id' => 'required|exists:properties,id',
            'check_in' => 'required|date_format:Y-m-d|after:today',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
            'adults' => 'required|integer|min:1|max:50',
            'children' => 'nullable|integer|min:0|max:50',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'guest.full_name.required' => 'Guest full name is required',
            'guest.email.required' => 'Guest email is required',
            'guest.email.email' => 'Guest email must be a valid email address',
            'guest.phone_e164.required' => 'Guest phone number is required',
            'property_id.required' => 'Property is required',
            'property_id.exists' => 'Selected property does not exist',
            'check_in.required' => 'Check-in date is required',
            'check_in.after' => 'Check-in date must be in the future',
            'check_out.required' => 'Check-out date is required',
            'check_out.after' => 'Check-out date must be after check-in date',
            'adults.required' => 'Number of adults is required',
            'adults.min' => 'At least 1 adult is required',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'children' => $this->children ?? 0,
        ]);
    }
}
