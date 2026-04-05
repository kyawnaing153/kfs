<?php

namespace App\Http\Requests\Backend\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Authorize request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [

            // Purchase Info
            'supplier_id'   => ['required', 'exists:suppliers,id'],
            'purchase_date' => ['required', 'date'],

            // Items
            'items' => ['required', 'array', 'min:1'],

            'items.*.product_variant_id' => [
                'required',
                'exists:product_variants,id'
            ],

            'items.*.unit' => [
                'required',
                'string',
                'max:50'
            ],

            'items.*.received_qty' => [
                'required',
                'numeric',
                'min:1'
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'items.*.total' => [
                'required',
                'numeric',
                'min:0'
            ],

            // Pricing
            'transport' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            // Status
            'payment_status' => [
                'required',
                'in:0,1'
            ],

            'status' => [
                'required',
                'in:0,1'
            ],

            // Totals
            'sub_total' => [
                'required',
                'numeric',
                'min:0'
            ],

            'total_amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            // Note
            'notes' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier is required.',
            'items.required'       => 'Please add at least one purchase item.',
            'items.*.product_variant_id.required' => 'Product variant is required.',
            'items.*.received_qty.min' => 'Quantity must be at least 1.',
        ];
    }
}