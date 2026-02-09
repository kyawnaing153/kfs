<?php

namespace App\Services;
use App\Repositories\StaffRepository;

class StaffService {
    protected $repo;

    public function __construct(StaffRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getStaffs($filters = [], $orderBy = 'id', $orderDir = 'desc'){
        return $this->repo->findAll($filters, $orderBy, $orderDir);
    }

    public function getStaff($id) {
        return $this->repo->findById($id);
    }

    public function createStaff($data) {
        return $this->repo->create($data);
    }

    public function updateStaff($id, $data) {
        return $this->repo->update($id, $data);
    }

    public function deleteStaff($id) {
        return $this->repo->delete($id);
    }

    public function toggleStaffStatus($id) {
        $staff = $this->repo->findById($id);
        if($staff) {
            $newStatus = $staff->status == 1 ? 0 : 1;
            $this->repo->update($id, ['status' => $newStatus]);
        }
    }
}