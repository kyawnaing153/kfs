<?php

namespace App\Http\Requests\Backend\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
        $userId = $this->route('supplier');

        // Base rules
        $rules = [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:9',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ];

        return $rules;
    }
}