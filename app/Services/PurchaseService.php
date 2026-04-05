<?php
// app/Services/PurchaseService.php

namespace App\Services;

use App\Repositories\{PurchaseRepository, SupplierRepository, ProductRepository};
use App\Models\Backend\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseService 
{
    protected $purchaseRepo;
    protected $supplierRepo;
    protected $productRepo;

    public function __construct(PurchaseRepository $purchaseRepo, SupplierRepository $supplierRepo, ProductRepository $productRepo)
    {
        $this->purchaseRepo = $purchaseRepo;
        $this->supplierRepo = $supplierRepo;
        $this->productRepo = $productRepo;
    }

    public function getPurchases($filters = [], $status = 'all', $perPage = 20)
    {
        return $this->purchaseRepo->getPurchases($filters, $status, $perPage);
    }

    public function getPurchase($id)
    {
        return $this->purchaseRepo->getPurchase($id);
    }

    public function createPurchase($data)
    {
        // Prepare purchase data
        $purchaseData = [
            'supplier_id' => $data['supplier_id'],
            'purchase_date' => $data['purchase_date'],
            'transport' => $data['transport'] ?? 0,
            'discount' => $data['discount'] ?? 0,
            'tax' => $data['tax'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'payment_status' => $data['payment_status'] ?? Purchase::PAYMENT_UNPAID,
            'status' => $data['status'] ?? Purchase::STATUS_PENDING,
            'user_id' => Auth::id(),
            'items' => $data['items'],
        ];
        
        return $this->purchaseRepo->create($purchaseData);
    }

    public function updatePurchase($id, $data)
    {
        $purchaseData = [
            'supplier_id' => $data['supplier_id'],
            'purchase_date' => $data['purchase_date'],
            'transport' => $data['transport'] ?? 0,
            'discount' => $data['discount'] ?? 0,
            'tax' => $data['tax'] ?? 0,
            'notes' => $data['notes'] ?? null,
            'payment_status' => $data['payment_status'] ?? Purchase::PAYMENT_UNPAID,
            'items' => $data['items'],
        ];
        
        return $this->purchaseRepo->update($id, $purchaseData);
    }

    public function deletePurchase($id)
    {
        return $this->purchaseRepo->delete($id);
    }

    public function markAsDelivered($id)
    {
        return $this->purchaseRepo->updateStatus($id, Purchase::STATUS_DELIVERED);
    }

    public function updatePaymentStatus($id, $paymentStatus)
    {
        return $this->purchaseRepo->updatePaymentStatus($id, $paymentStatus);
    }

    public function getPurchaseStatistics()
    {
        return $this->purchaseRepo->getPurchaseStatistics();
    }

    public function generatePurchaseCode()
    {
        return Purchase::generatePurchaseCode();
    }

    public function getSuppliersForDropdown()
    {
        return $this->supplierRepo->getActiveSuppliersForDropdown();
    }

    public function getAvailableProductVariants()
    {
        return \App\Models\ProductVariant::with('product')
            ->get();
    }
}