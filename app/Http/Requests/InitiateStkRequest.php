<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiateStkRequest extends FormRequest
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
            'payment_intent_id' => 'required|exists:payment_intents,id',
            'phone_e164' => 'required|string|regex:/^\+254\d{9}$/',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_intent_id.required' => 'Payment intent is required',
            'payment_intent_id.exists' => 'Payment intent does not exist',
            'phone_e164.required' => 'Phone number is required',
            'phone_e164.regex' => 'Phone number must be in E.164 format (e.g., +254712345678)',
        ];
    }
}
