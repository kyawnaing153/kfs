<?php

namespace App\Services;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerService {
    protected $repo;

    public function __construct(CustomerRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


    public function getCustomers($filters = [], $orderBy = 'id', $orderDir = 'desc') {
        return $this->repo->findAll($filters, $orderBy, $orderDir);
    }

    public function getCustomer($id) {
        return $this->repo->findById($id);
    }

    public function createCustomer($data) {
        return $this->repo->create($data);
    }

    public function updateCustomer($id, $data) {
        return $this->repo->update($id, $data);
    }

    public function deleteCustomer($id) {
        return $this->repo->delete($id);
    }

    public function toggleCustomerStatus($id) {
        $customer = $this->repo->findById($id);
        if ($customer) {
            $newStatus = $customer->status == 1 ? 0 : 1;
            $this->repo->update($id, ['status' => $newStatus]);
        }
    }
}