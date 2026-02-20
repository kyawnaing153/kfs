<?php

namespace App\Http\Requests\Backend\Sale;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends StoreSaleRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        
        // Remove required for sale_code since it's generated automatically
        unset($rules['sale_code']);
        
        return $rules;
    }
}