<?php

namespace App\Http\Requests\Backend\Rent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentPaymentRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'amount' => 'required|numeric|min:1',
            'payment_method' => [
                'required',
                'string',
                Rule::in(['cash', 'bank_transfer', 'card', 'mobile_payment', 'cheque', 'other'])
            ],
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_for' => 'nullable|string|max:100',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'note' => 'nullable|string|max:500',
        ];

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'amount' => 'payment amount',
            'payment_method' => 'payment method',
            'payment_date' => 'payment date',
            'payment_for' => 'payment for',
            'period_start' => 'period start date',
            'period_end' => 'period end date',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'amount.max' => 'The payment amount cannot exceed the due amount.',
            'payment_date.before_or_equal' => 'Payment date cannot be in the future.',
            'period_end.after_or_equal' => 'Period end date must be after or equal to period start date.',
        ];
    }

    /**
     * Get the maximum allowed payment amount based on rent due
     */
    private function getMaxAmount(): float
    {
        if ($this->route('rent')) {
            return $this->route('rent')->total_due;
        }
        return 999999999.99; // Default max value
    }
}