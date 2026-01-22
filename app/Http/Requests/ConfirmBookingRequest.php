<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmBookingRequest extends FormRequest
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
            'special_requests' => 'nullable|string|max:1000',
            'adults' => 'nullable|integer|min:1|max:50',
            'children' => 'nullable|integer|min:0|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'adults.min' => 'At least 1 adult is required',
            'children.min' => 'Children count cannot be negative',
        ];
    }
}
