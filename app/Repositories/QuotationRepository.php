<?php

namespace App\Repositories;

use App\Models\Frontend\Quotation;
use App\Repositories\Interfaces\QuotationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class QuotationRepository implements QuotationRepositoryInterface
{
    protected Quotation $model;

    public function __construct(Quotation $model)
    {
        $this->model = $model;
    }

    /**
     * Get customer quotations with optional search
     */
    public function getCustomerQuotations(string $search = null): LengthAwarePaginator
    {
        $query = $this->model->with(['customer', 'items.productVariant']);

        if ($search && !empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('quotation_code', 'like', "%{$search}%");
            });
        }
        
        return $query->orderBy('created_at', 'desc')->paginate(15);
    }
}