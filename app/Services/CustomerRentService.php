<?php

namespace App\Services;

use App\Repositories\Interfaces\CustomerRentRepositoryInterface;

class CustomerRentService
{
    protected CustomerRentRepositoryInterface $customerRentRepository;

    public function __construct(CustomerRentRepositoryInterface $customerRentRepository)
    {
        $this->customerRentRepository = $customerRentRepository;
    }

    public function getRentsByCustomerId($customerId)
    {
        return $this->customerRentRepository->getRentsByCustomerId($customerId);
    }
}