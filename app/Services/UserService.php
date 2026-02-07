<?php

namespace App\Services;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService {
    protected $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


    public function getUsers($filters = [], $orderBy = 'id', $orderDir = 'desc') {
        return $this->repo->findAll($filters, $orderBy, $orderDir);
    }

    public function getUser($id) {
        return $this->repo->findById($id);
    }

    public function createUser($data) {
        return $this->repo->create($data);
    }

    public function updateUser($id, $data) {
        return $this->repo->update($id, $data);
    }

    public function deleteUser($id) {
        return $this->repo->delete($id);
    }

    public function toggleUserStatus($id) {
        $user = $this->repo->findById($id);
        if ($user) {
            $newStatus = $user->status == 1 ? 0 : 1;
            $this->repo->update($id, ['status' => $newStatus]);
        }
    }
}