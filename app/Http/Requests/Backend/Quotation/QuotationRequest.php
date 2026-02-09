<?php

namespace App\Http\Requests\Backend\Quotation;

use Illuminate\Foundation\Http\FormRequest;

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
     */
    public function rules(): array
    {
        return [
            'company_email' => 'required|email|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_name' => 'required|string|max:255',
            'client_address' => 'nullable|string|max:500',
            'client_phone' => 'nullable|string|max:20',
            'quotation_title' => 'required|string|max:255',
            'quotation_no' => 'required|string|max:50',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'secure_deposit' => 'nullable|numeric|min:0',
            'transport_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'terms' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required.',
            'items.*.name.required' => 'Item name is required.',
            'items.*.quantity.required' => 'Item quantity is required.',
            'items.*.quantity.min' => 'Item quantity must be at least 0.01.',
            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.min' => 'Unit price must be at least 0.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'company_email' => 'company email',
            'client_email' => 'client email',
            'client_name' => 'client name',
            'quotation_title' => 'quotation title',
            'quotation_no' => 'quotation number',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Ensure numeric fields are properly formatted
        $this->merge([
            'secure_deposit' => (float) ($this->secure_deposit ?? 0),
            'transport_fee' => (float) ($this->transport_fee ?? 0),
            'discount' => (float) ($this->discount ?? 0),
            'tax_percentage' => (float) ($this->tax_percentage ?? 0),
        ]);

        // Ensure item numeric fields are properly formatted
        if ($this->items) {
            $items = [];
            foreach ($this->items as $index => $item) {
                $items[$index] = [
                    'name' => $item['name'] ?? '',
                    'quantity' => (float) ($item['quantity'] ?? 1),
                    'unit' => $item['unit'] ?? 'pcs',
                    'unit_price' => (float) ($item['unit_price'] ?? 0),
                ];
            }
            $this->merge(['items' => $items]);
        }
    }
}