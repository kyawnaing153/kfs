<?php

namespace App\Http\Requests\Backend\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true if you want to authorize all users
        // Or add your authorization logic here
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // For update/store operations
        $staffId = $this->route('staff');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2'
            ],
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('staff', 'phone_number')->ignore($staffId),
                'regex:/^[0-9\-\+\s\(\)]{10,20}$/'
            ],
            'department' => [
                'required',
                'string',
                'max:100'
            ],
            'salary' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.99'
            ],
            'address' => [
                'nullable',
                'string',
                'max:500'
            ],
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp,svg',
                'max:2048', // 2MB max
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            'status' => [
                'required',
                'boolean',
                'in:0,1'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The staff name is required.',
            'name.min' => 'The name must be at least 2 characters.',
            'name.max' => 'The name may not be greater than 255 characters.',
            
            'phone_number.required' => 'The phone number is required.',
            'phone_number.unique' => 'This phone number is already registered.',
            'phone_number.regex' => 'Please enter a valid phone number format.',
            'phone_number.max' => 'Phone number may not be greater than 20 characters.',
            
            'department.required' => 'The department field is required.',
            'department.max' => 'Department may not be greater than 100 characters.',
            
            'salary.required' => 'The salary field is required.',
            'salary.numeric' => 'Salary must be a number.',
            'salary.min' => 'Salary must be at least 0.',
            'salary.max' => 'Salary may not be greater than 9,999,999.99.',
            
            'address.max' => 'Address may not be greater than 500 characters.',
            
            'profile_picture.image' => 'The file must be an image.',
            'profile_picture.mimes' => 'Only JPEG, PNG, JPG, GIF, WebP, and SVG files are allowed.',
            'profile_picture.max' => 'The image may not be greater than 2MB.',
            'profile_picture.dimensions' => 'Image dimensions must be between 100x100 and 2000x2000 pixels.',
            
            'status.required' => 'The status field is required.',
            'status.boolean' => 'Status must be either active or inactive.',
            'status.in' => 'Invalid status value.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'staff name',
            'phone_number' => 'phone number',
            'department' => 'department',
            'salary' => 'salary',
            'address' => 'address',
            'profile_picture' => 'profile picture',
            'status' => 'status'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Trim string inputs
        $this->merge([
            'name' => trim($this->name),
            'phone_number' => trim($this->phone_number),
            'department' => trim($this->department),
            'address' => trim($this->address),
            
            // Ensure salary is properly formatted
            'salary' => $this->salary ? (float) str_replace(',', '', $this->salary) : null,
            
            // Ensure status is boolean (0 or 1)
            'status' => $this->status ? (int) $this->status : 0
        ]);
    }

    /**
     * Get validated data with additional processing.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Remove profile_picture from validated data if it's null/empty
        // The actual file handling should be done in the controller
        if (isset($validated['profile_picture']) && empty($validated['profile_picture'])) {
            unset($validated['profile_picture']);
        }
        
        return $validated;
    }
}