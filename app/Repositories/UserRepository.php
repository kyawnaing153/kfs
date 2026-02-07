<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id)
    {
        return User::find($id);
    }

    public function findAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc')
    {
        $query = User::query();
        // Handle search across multiple fields
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Handle status filter (exact match)
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']); // No 'like' for status
        }

        if(isset($filters['role'])) {
            $query->where('role', $filters['role']); // No 'like' for role
        }
        
        return $query->orderBy($orderBy, $orderDir);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(int $id, array $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);
            return $user;
        }
        return null;
    }

    public function delete(int $id)
    {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}