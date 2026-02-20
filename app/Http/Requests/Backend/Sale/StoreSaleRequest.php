<?php

namespace App\Http\Requests\Backend\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'sub_total' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'transport' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'total_paid' => 'required|numeric|min:0',
            'total_due' => 'required|numeric|min:0',
            'payment_type' => 'nullable|string|max:50',
            'status' => 'nullable|in:pending,completed',
            
            'items' => 'required|array|min:1',
            'items.*.product_variant_id' => 'required|exists:product_variants,id',
            'items.*.sale_qty' => 'required|integer|min:1',
            'items.*.unit' => 'nullable|string|max:20',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'At least one item is required for the sale.',
            'items.*.product_variant_id.required' => 'Product variant is required.',
            'items.*.sale_qty.required' => 'Quantity is required.',
            'items.*.sale_qty.min' => 'Quantity must be at least 1.',
            'items.*.unit_price.required' => 'Unit price is required.',
        ];
    }
}