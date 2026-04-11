<?php

namespace App\Repositories;

use App\Models\Backend\Rent;
use App\Repositories\Interfaces\CustomerRentRepositoryInterface;

class CustomerRentRepository implements CustomerRentRepositoryInterface
{
    protected Rent $rentModel;

    public function __construct(Rent $rentModel)
    {
        $this->rentModel = $rentModel;
    }

    public function getRentsByCustomerId($customerId)
    {
        return $this->rentModel
            ->where('customer_id', $customerId)
            ->with(['items', 'payments', 'returns'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}