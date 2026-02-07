<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function findById(int $id)
    {
        return Customer::find($id);
    }

    public function findAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc')
    {
        $query = Customer::query();
        // Handle search across multiple fields
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // Handle status filter (exact match)
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']); // No 'like' for status
        }

        return $query->orderBy($orderBy, $orderDir);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->update($data);
            return $customer;
        }
        return null;
    }

    public function delete(int $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            return $customer->delete();
        }
        return false;
    }
}
