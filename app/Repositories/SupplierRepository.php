<?php

namespace App\Repositories;

use App\Models\Backend\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function findById(int $id)
    {
        return Supplier::find($id);
    }

    public function findAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc')
    {
        $query = Supplier::query();
        // Handle search across multiple fields
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
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
        return Supplier::create($data);
    }

    public function update(int $id, array $data)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->update($data);
            return $supplier;
        }
        return null;
    }

    public function delete(int $id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            return $supplier->delete();
        }
        return false;
    }
}
