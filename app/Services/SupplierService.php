<?php

namespace App\Services;
use App\Repositories\SupplierRepository;

class SupplierService {
    protected $repo;

    public function __construct(SupplierRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getSuppliers($filters = [], $orderBy = 'id', $orderDir = 'desc'){
        return $this->repo->findAll($filters, $orderBy, $orderDir);
    }

    public function getSupplier($id) {
        return $this->repo->findById($id);
    }

    public function createSupplier($data) {
        return $this->repo->create($data);
    }

    public function updateSupplier($id, $data) {
        return $this->repo->update($id, $data);
    }

    public function deleteSupplier($id) {
        return $this->repo->delete($id);
    }

    public function toggleSupplierStatus($id) {
        $supplier = $this->repo->findById($id);
        if ($supplier) {
            $newStatus = $supplier->status == 1 ? 0 : 1;
            $this->repo->update($id, ['status' => $newStatus]);
        }
    }

    public function getActiveSuppliersForDropdown() {
        return $this->repo->getActiveSuppliersForDropdown();
    }
}