<?php

namespace App\Http\Requests\Backend\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        // Get customer ID for update (ignore unique email rule)
        $userId = $this->route('customer');

        // Base rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($userId),
            ],
            'phone_number' => 'required|string|min:9',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:0,1',
        ];

        // Password rules: required for create, optional for update
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        return $rules;
    }
}