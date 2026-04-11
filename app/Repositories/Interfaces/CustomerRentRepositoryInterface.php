<?php

namespace App\Repositories\Interfaces;

interface CustomerRentRepositoryInterface
{
    public function getRentsByCustomerId($customerId);
}