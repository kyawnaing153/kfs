<?php

namespace App\Http\Requests\Backend\Product;

use Illuminate\Foundation\Http\FormRequest;

class VariantPriceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'price_type' => 'required|in:sale,rent',
            'duration_days' => 'required_if:price_type,rent|nullable|integer|min:1',
            'price' => 'required|numeric|min:0.1',
        ];
    }

    public function messages()
    {
        return [
            'price_type.required' => 'Price type is required.',
            'price_type.in' => 'Price type must be sale or rent.',
            'duration_days.required_if' => 'Duration days is required for rental prices.',
            'duration_days.min' => 'Duration must be at least 1 day.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be greater than 0.',
        ];
    }
}