<?php

namespace App\Http\Controllers;

use App\Services\CustomerRentService;
use App\Models\Customer;

class CustomerRentController extends Controller
{
    protected CustomerRentService $customerRentService;

    public function __construct(CustomerRentService $customerRentService)
    {
        $this->customerRentService = $customerRentService;
    }

    public function index($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $rents = $this->customerRentService->getRentsByCustomerId($customerId);

        return view('pages.admin.customers.customer-rents', compact('customer', 'rents'));
    }
}