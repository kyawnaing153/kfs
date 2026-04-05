<?php

namespace App\Http\Requests\Backend\Expense;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_title' => ['required', 'string', 'max:255'],
            'amount'        => ['required', 'numeric', 'min:0'],
            'expense_date'  => ['required', 'date', 'before_or_equal:today'],
            'status'        => ['nullable', 'in:0,1'],
            'note'          => ['nullable', 'string', 'max:1000'],
        ];
    }

    // Clean validated data (modern Laravel way)
    public function validatedData(): array
    {
        return [
            ...$this->validated(),
            'status' => $this->status ?? 1, // default Active
        ];
    }

    // Custom error messages
    public function messages(): array
    {
        return [
            'expense_title.required' => 'Expense title is required.',
            'amount.required'        => 'Amount is required.',
            'amount.numeric'         => 'Amount must be a number.',
            'expense_date.required'  => 'Expense date is required.',
            'expense_date.before_or_equal' => 'Expense date cannot be in the future.',
        ];
    }

    // Optional: Clean input before validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'expense_title' => trim($this->expense_title),
            'note'          => $this->note ? trim($this->note) : null,
        ]);
    }
}