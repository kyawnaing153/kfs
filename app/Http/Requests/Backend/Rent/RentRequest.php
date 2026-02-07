<?php

namespace App\Http\Requests\Backend\Rent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentRequest extends FormRequest
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
        // Determine if this is a store or update request
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            // Customer and Date
            'customer_id' => [
                'required',
                'integer',
                'exists:customers,id'
            ],
            'rent_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],

            // Items validation
            'items' => [
                'required',
                'array',
                'min:1'
            ],
            'items.*.product_variant_id' => [
                'required',
                'integer',
                'exists:product_variants,id'
            ],
            'items.*.rent_qty' => [
                'required',
                'integer',
                'min:1',
                'max:9999'
            ],
            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0.1',
                'max:999999.9'
            ],
            'items.*.unit' => [
                'nullable',
                'string',
                'max:20'
            ],
            'items.*.total' => [
                'required',
                'numeric',
                'min:0.1',
                'max:999999.9'
            ],

            // Financial fields
            'sub_total' => [
                'required',
                'numeric',
                'min:0.1',
                'max:9999999.9'
            ],
            'discount' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],
            'deposit' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],
            'transport' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],
            'total' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],
            'total_paid' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],
            'total_due' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],

            // Payment type
            'payment_type' => [
                'required',
                'string',
                Rule::in(['cash', 'card', 'bank_transfer', 'mobile_payment', 'credit'])
            ],

            // Document (optional)
            'document' => [
                'nullable',
                'string',
                'max:255'
            ],

            // Status (only for updates)
            'status' => [
                'sometimes',
                'required_if:is_update,true',
                Rule::in(['pending', 'ongoing', 'completed'])
            ],

            // Notes
            'note' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];

        // For store requests, add stock validation
        if (!$isUpdate) {
            $rules['items.*.product_variant_id'][] = function ($attribute, $value, $fail) {
                // Check stock availability (you need to implement this)
                // This is a placeholder - implement based on your ProductVariant model
                $variant = \App\Models\ProductVariant::find($value);
                if (!$variant || $variant->qty <= 0) {
                    $fail('Selected product variant is out of stock or does not exist.');
                }
            };

            // Add validation for sufficient stock quantity
            $rules['items.*.rent_qty'][] = function ($attribute, $value, $fail) {
                $index = explode('.', $attribute)[1];
                $variantId = $this->input("items.{$index}.product_variant_id");

                if ($variantId) {
                    $variant = \App\Models\ProductVariant::find($variantId);
                    if ($variant && $value > $variant->qty) {
                        $fail("Insufficient stock for {$variant->product->product_name}. Available: {$variant->qty}");
                    }
                }
            };
        }

        return $rules;
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a customer.',
            'customer_id.exists' => 'Selected customer does not exist.',

            'rent_date.required' => 'Rent date is required.',
            'rent_date.before_or_equal' => 'Rent date cannot be in the future.',

            'items.required' => 'At least one item is required for the rent.',
            'items.*.product_variant_id.required' => 'Please select a product for each item.',
            'items.*.product_variant_id.exists' => 'Selected product does not exist.',
            'items.*.rent_qty.required' => 'Quantity is required.',
            'items.*.rent_qty.min' => 'Quantity must be at least 1.',
            'items.*.unit_price.required' => 'Unit price is required.',
            'items.*.unit_price.min' => 'Unit price must be at least 0.1.',

            'sub_total.required' => 'Sub total is required.',
            'discount.required' => 'Discount is required (enter 0 if no discount).',
            'deposit.required' => 'Deposit is required (enter 0 if no deposit).',
            'transport.required' => 'Transport cost is required (enter 0 if no transport cost).',
            'total.required' => 'Total amount is required.',
            'total_due.required' => 'Total due amount is required.',
            'total_paid.required' => 'Total paid amount is required.',

            'payment_type.required' => 'Please select a payment type.',
            'payment_type.in' => 'Invalid payment type selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure numeric fields are properly formatted
        $this->merge([
            'sub_total' => (float) $this->input('sub_total', 0),
            'discount' => (float) $this->input('discount', 0),
            'deposit' => (float) $this->input('deposit', 0),
            'transport' => (float) $this->input('transport', 0),
            'total' => (float) $this->input('total', 0),
            'total_paid' => (float) $this->input('total_paid', 0),
            'total_due' => (float) $this->input('total_due', 0),
        ]);

        // Calculate item totals if not provided
        if ($this->has('items')) {
            $items = $this->input('items', []);

            foreach ($items as $index => $item) {
                if (isset($item['rent_qty'], $item['unit_price'])) {
                    $items[$index]['total'] = (float) ($item['rent_qty'] * $item['unit_price']);
                }
            }

            $this->merge(['items' => $items]);
        }
    }

    /**
     * Custom validation after rules are applied.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that total = transport + deposit
            if ($this->has(['transport', 'deposit', 'total'])) {
                $expectedTotal = $this->input('transport') + $this->input('deposit');
                if (abs($expectedTotal - $this->input('total')) > 0.1) {
                    $validator->errors()->add('total', 'Total must be transport + deposit.');
                }
            }

            // Validate that total_due = total - total_paid
            if ($this->has(['total', 'total_paid', 'total_due'])) {
                $expectedDue = $this->input('total') - $this->input('total_paid');
                if (abs($expectedDue - $this->input('total_due')) > 0.1) {
                    $validator->errors()->add('total_due', 'Total due must be total minus total paid.');
                }
            }

            // Validate that sub_total matches sum of item totals
            if ($this->has(['items', 'sub_total'])) {
                $itemsTotal = 0;
                foreach ($this->input('items', []) as $item) {
                    if (isset($item['total'])) {
                        $itemsTotal += $item['total'];
                    } elseif (isset($item['rent_qty'], $item['unit_price'])) {
                        $itemsTotal += $item['rent_qty'] * $item['unit_price'];
                    }
                }
                if (abs($itemsTotal - $this->input('sub_total')) > 0.1) {
                    $validator->errors()->add('sub_total', 'Sub total does not match sum of item totals.');
                }
            }

            // Prevent duplicate product variants in the same rent
            if ($this->has('items')) {
                $variantIds = array_column($this->input('items'), 'product_variant_id');
                $duplicates = array_diff_assoc($variantIds, array_unique($variantIds));
                if (!empty($duplicates)) {
                    $validator->errors()->add('items', 'Duplicate products found. Please combine quantities for the same product.');
                }
            }

            // Validate deposit doesn't exceed total
            if ($this->has(['deposit', 'total']) && $this->input('deposit') > $this->input('total')) {
                $validator->errors()->add('deposit', 'Deposit cannot exceed total amount.');
            }

            // Validate discount doesn't exceed sub_total
            if ($this->has(['discount', 'sub_total']) && $this->input('discount') > $this->input('sub_total')) {
                $validator->errors()->add('discount', 'Discount cannot exceed sub total.');
            }
        });
    }

    /**
     * Get validated data with additional processing.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Add generated rent code for store operations
        if (!$this->isMethod('PUT') && !$this->isMethod('PATCH')) {
            $validated['rent_code'] = $this->generateRentCode();
            $validated['total_paid'] = $this->input('total_paid', 0);
            $validated['status'] = 'pending';
        }

        // Ensure proper decimal precision
        $decimalFields = [
            'sub_total',
            'discount',
            'deposit',
            'transport',
            'total',
            'total_paid',
            'total_due'
        ];

        foreach ($decimalFields as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = round($validated[$field], 1);
            }
        }

        return $validated;
    }

    /**
     * Generate unique rent code.
     */
    private function generateRentCode(): string
    {
        $prefix = 'RENT-' . date('Ym');
        $lastRent = \App\Models\Backend\Rent::where('rent_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRent) {
            $lastNumber = (int) substr($lastRent->rent_code, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . '-' . $newNumber;
    }
}
