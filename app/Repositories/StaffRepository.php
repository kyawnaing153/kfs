<?php

namespace App\Repositories;

use App\Models\Backend\Staff;
use App\Repositories\Interfaces\StaffRepositoryInterface;

class StaffRepository implements StaffRepositoryInterface
{
    public function findById(int $id)
    {
        return Staff::find($id);
    }

    public function findAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc')
    {
        $query = Staff::query();
        // Handle search across multiple fields
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('salary', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
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
        return Staff::create($data);
    }

    public function update(int $id, array $data)
    {
        $staff = Staff::find($id);
        if ($staff) {
            $staff->update($data);
            return $staff;
        }
        return null;
    }

    public function delete(int $id)
    {
        $staff = Staff::find($id);
        if ($staff) {
            return $staff->delete();
        }
        return false;
    }
}