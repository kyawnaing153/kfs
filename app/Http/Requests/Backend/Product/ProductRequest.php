<?php

namespace App\Http\Requests\Backend\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'product_name' => 'required|string|max:255',
            'product_type' => ['required', Rule::in(['sale', 'rent', 'both'])],
            'description' => 'nullable|string',
            'thumb' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'boolean',
            'is_feature' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];

        // For update, make thumb optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['thumb'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Product name is required.',
            'product_type.required' => 'Product type is required.',
            'product_type.in' => 'Product type must be sale, rent, or both.',
            'thumb.image' => 'The file must be an image.',
            'thumb.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'thumb.max' => 'The image may not be greater than 2MB.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->boolean('status'),
            'is_feature' => $this->boolean('is_feature'),
        ]);
    }
}