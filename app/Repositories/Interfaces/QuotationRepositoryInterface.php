<?php

namespace App\Repositories\Interfaces;

use App\Models\Frontend\Quotation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QuotationRepositoryInterface
{
    /**
     * Get customer quotations with optional search
     */
    public function getCustomerQuotations(string $search = null): LengthAwarePaginator;
}   