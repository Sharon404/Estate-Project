<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitManualMpesaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public endpoint for guests
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payment_intent_id' => 'required|exists:payment_intents,id',
            'mpesa_receipt_number' => 'required|string|min:9|max:20|regex:/^[A-Z0-9]{9,20}$/',
            'amount' => 'required|numeric|min:1|max:999999.99',
            'phone_e164' => 'nullable|regex:/^\+254\d{9}$/',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'mpesa_receipt_number.required' => 'M-PESA receipt number is required',
            'mpesa_receipt_number.regex' => 'M-PESA receipt must be 9-20 alphanumeric characters (e.g., LIK123ABC456)',
            'mpesa_receipt_number.max' => 'M-PESA receipt cannot exceed 20 characters',
            'amount.required' => 'Payment amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'amount.min' => 'Amount must be at least 1',
            'phone_e164.regex' => 'Phone number must be in E.164 format (e.g., +254712345678)',
        ];
    }
}
