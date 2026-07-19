<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method'    => ['required', 'in:gcash,paymaya,bank_transfer,credit_card'],
            'payment_reference' => ['required', 'string', 'min:4', 'max:40', 'regex:/^[A-Za-z0-9\-]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required'    => 'Please choose a payment method.',
            'payment_method.in'          => 'Please choose a valid payment method.',
            'payment_reference.required' => 'Please enter your payment reference / transaction number.',
            'payment_reference.min'      => 'The reference number looks too short — please check it.',
            'payment_reference.regex'    => 'The reference number should only contain letters, numbers, and dashes.',
        ];
    }
}
