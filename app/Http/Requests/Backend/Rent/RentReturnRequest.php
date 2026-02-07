<?php

namespace App\Http\Requests\Backend\Rent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RentReturnRequest extends FormRequest
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
        $rent = $this->route('rent');

        return [
            'return_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($rent) {
                    $returnDate = new \DateTime($value);
                    $rentDate = new \DateTime($rent->rent_date);
                    
                    if ($returnDate < $rentDate) {
                        $fail('Return date cannot be before the rent date (' . $rent->rent_date . ').');
                    }
                }
            ],

            'return_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048'
            ],

            'status' => [
                'required',
                Rule::in(['partial', 'completed'])
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'selected_items' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $selectedItems = json_decode($value, true);
                    if (!is_array($selectedItems) || count($selectedItems) === 0) {
                        $fail('Please select at least one item to return.');
                    }
                }
            ],

            'items' => [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) use ($rent) {
                    $selectedItems = json_decode($this->input('selected_items', '[]'), true);
                    
                    // Check if all selected items exist in the items array
                    foreach ($selectedItems as $itemId) {
                        $found = false;
                        foreach ($value as $item) {
                            if (($item['rent_item_id'] ?? null) == $itemId) {
                                $found = true;
                                break;
                            }
                        }
                        
                        if (!$found) {
                            $fail("Item #{$itemId} is selected but not found in the request.");
                        }
                    }
                }
            ],

            'items.*.rent_item_id' => [
                'required',
                'integer',
                'exists:rent_items,id',
                function ($attribute, $value, $fail) use ($rent) {
                    $rentItem = \App\Models\Backend\RentItem::find($value);
                    if (!$rentItem || $rentItem->rent_id !== $rent->id) {
                        $fail('Invalid rent item selected.');
                    }
                }
            ],

            'items.*.qty' => [
                'required',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $rentItemId = $this->input("items.{$index}.rent_item_id");
                    
                    // Get selected items
                    $selectedItems = json_decode($this->input('selected_items', '[]'), true);
                    $isSelected = in_array($rentItemId, $selectedItems);
                    
                    if ($rentItemId) {
                        $rentItem = \App\Models\Backend\RentItem::find($rentItemId);
                        if ($rentItem) {
                            $remainingQty = $rentItem->rent_qty - $rentItem->returned_qty;
                            
                            // For selected items, validate quantity
                            if ($isSelected) {
                                if ($value <= 0) {
                                    $fail("Return quantity must be greater than 0 for selected items.");
                                }
                                
                                if ($value > $remainingQty) {
                                    $fail("Cannot return {$value} items. Only {$remainingQty} remaining.");
                                }
                            } else {
                                // For non-selected items, quantity must be 0
                                if ($value != 0) {
                                    $fail("Return quantity must be 0 for non-selected items.");
                                }
                            }
                        }
                    }
                }
            ],

            'items.*.damage_fee' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999.9',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $rentItemId = $this->input("items.{$index}.rent_item_id");
                    
                    // Get selected items
                    $selectedItems = json_decode($this->input('selected_items', '[]'), true);
                    $isSelected = in_array($rentItemId, $selectedItems);
                    
                    // Damage fee should only be set for selected items
                    if (!$isSelected && $value > 0) {
                        $fail("Damage fee should only be set for selected items.");
                    }
                }
            ],

            'items.*.note' => [
                'nullable',
                'string',
                'max:500'
            ],

            'transport' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],

            'refund_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],

            'collect_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999.9'
            ],

            'total_days' => [
                'required',
                'integer',
                'min:0'
            ]
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'return_date.required' => 'Return date is required.',
            'return_date.date' => 'Please enter a valid return date.',

            'return_image.image' => 'Please upload a valid image file.',
            'return_image.mimes' => 'Image must be a JPEG, PNG, JPG, GIF, or WebP file.',
            'return_image.max' => 'Image size must be less than 2MB.',

            'status.required' => 'Please select return status.',
            'status.in' => 'Invalid return status selected.',

            'items.required' => 'At least one item must be returned.',
            'items.min' => 'At least one item must be returned.',

            'items.*.rent_item_id.required' => 'Please select a rent item.',
            'items.*.rent_item_id.exists' => 'Invalid rent item selected.',

            'items.*.qty.required' => 'Return quantity is required.',
            'items.*.qty.min' => 'Return quantity cannot be negative.',

            'items.*.damage_fee.min' => 'Damage fee cannot be negative.',
            'items.*.damage_fee.max' => 'Damage fee is too high.',

            'transport.required' => 'Transport amount is required.',
            'transport.min' => 'Transport amount cannot be negative.',
            'transport.max' => 'Transport amount is too high.',

            'refund_amount.required' => 'Refund amount is required.',
            'refund_amount.min' => 'Refund amount cannot be negative.',
            'refund_amount.max' => 'Refund amount is too high.',

            'collect_amount.required' => 'Collect amount is required.',
            'collect_amount.min' => 'Collect amount cannot be negative.',
            'collect_amount.max' => 'Collect amount is too high.',

            'total_days.required' => 'Total rental days is required.',
            'total_days.min' => 'Total rental days must be at least 0.',
        ];
    }

    /**
     * Custom validation after rules are applied.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that at least some quantity is being returned from selected items
            if ($this->has('items') && $this->has('selected_items')) {
                $selectedItems = json_decode($this->input('selected_items', '[]'), true);
                $totalQty = 0;
                
                foreach ($this->input('items', []) as $item) {
                    if (in_array($item['rent_item_id'] ?? null, $selectedItems)) {
                        $totalQty += $item['qty'] ?? 0;
                    }
                }

                if ($totalQty <= 0) {
                    $validator->errors()->add('items', 'Please enter return quantities for at least one item.');
                }
            }

            // Validate that refund and collect amounts are not both set
            $refundAmount = (float) $this->input('refund_amount', 0);
            $collectAmount = (float) $this->input('collect_amount', 0);

            if ($refundAmount > 0 && $collectAmount > 0) {
                $validator->errors()->add('refund_amount', 'Only one of Refund Amount or Collect Amount can be greater than 0.');
                $validator->errors()->add('collect_amount', 'Only one of Refund Amount or Collect Amount can be greater than 0.');
            }
        });
    }

    /**
     * Get validated data with additional processing.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Filter out non-selected items
        if (isset($validated['items']) && isset($validated['selected_items'])) {
            $selectedItems = json_decode($validated['selected_items'], true);
            $filteredItems = [];
            
            foreach ($validated['items'] as $item) {
                if (in_array($item['rent_item_id'] ?? null, $selectedItems)) {
                    $filteredItems[] = $item;
                }
            }
            
            $validated['items'] = $filteredItems;
        }
        
        // Remove selected_items from validated data as it's only for validation
        unset($validated['selected_items']);

        // Ensure proper decimal precision
        $decimalFields = [
            'transport',
            'refund_amount',
            'collect_amount'
        ];

        foreach ($decimalFields as $field) {
            if (isset($validated[$field])) {
                $validated[$field] = round((float) $validated[$field], 1);
            }
        }

        // Process damage fees for items
        if (isset($validated['items'])) {
            foreach ($validated['items'] as &$item) {
                if (isset($item['damage_fee'])) {
                    $item['damage_fee'] = round((float) $item['damage_fee'], 1);
                }
                // Ensure qty is integer
                if (isset($item['qty'])) {
                    $item['qty'] = (int) $item['qty'];
                }
            }
        }

        // Ensure total_days is integer
        if (isset($validated['total_days'])) {
            $validated['total_days'] = (int) $validated['total_days'];
        }

        // Store return image if uploaded
        if ($this->hasFile('return_image')) {
            $path = $this->file('return_image')->store('returns', 'public');
            $validated['return_image'] = $path;
        }

        return $validated;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'return_date' => 'return date',
            'return_image' => 'return image',
            'status' => 'status',
            'note' => 'notes',
            'items' => 'return items',
            'items.*.rent_item_id' => 'rent item',
            'items.*.qty' => 'return quantity',
            'items.*.damage_fee' => 'damage fee',
            'items.*.note' => 'item notes',
            'transport' => 'transport amount',
            'refund_amount' => 'refund amount',
            'collect_amount' => 'collect amount',
            'total_days' => 'total rental days',
        ];
    }
}