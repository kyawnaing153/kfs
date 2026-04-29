<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuotationRequest extends FormRequest
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
        return [
            #'quotation_code'     => ['required', 'string', 'unique:quotations,quotation_code'],
            'type'               => ['required', Rule::in(['rent', 'purchase'])],
            'customer_name'      => ['nullable', 'string', 'max:255'],
            'customer_id'        => ['nullable', 'exists:customers,id'],
            'rent_date'          => ['required_if:type,rent', 'nullable', 'date'],
            'rent_duration'      => ['required_if:type,rent', 'nullable', 'integer', 'min:1'],
            'transport_required' => ['nullable', 'boolean'],
            'transport_address'  => ['nullable', 'string'],
            'notes'              => ['nullable', 'string'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'exists:product_variants,id'],
            'items.*.qty'        => ['required', 'integer', 'min:1'],
        ];
    }
}