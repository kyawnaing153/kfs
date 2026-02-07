<?php

namespace App\Http\Requests\Backend\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductVariantRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $variantId = $this->route('variant');

        $rules = [
            'size' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'qty' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            // 'sku' => [
            //     'required',
            //     'string',
            //     'max:100',
            //     Rule::unique('product_variants', 'sku')->ignore($this->route('variantId'), 'id')
            // ],
        ];

        if ($this->isMethod('POST')) {
            $rules['sku'] = [
                'required',
                'string',
                'max:100',
                Rule::unique('product_variants', 'sku')
            ];
        } else if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            if ($this->has('sku')) {
                $rules['sku'] = [
                    'sometimes',
                    'string',
                    'max:100',
                    Rule::unique('product_variants', 'sku')->ignore($variantId)
                ];
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU already exists.',
            'qty.required' => 'Quantity is required.',
            'qty.min' => 'Quantity cannot be negative.',
            'purchase_price.required' => 'Purchase price is required.',
            'purchase_price.min' => 'Purchase price cannot be negative.',
        ];
    }
}