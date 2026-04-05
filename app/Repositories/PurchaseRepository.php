<?php
// app/Repositories/PurchaseRepository.php

namespace App\Repositories;

use App\Models\Backend\{Purchase, PurchaseItem};
use App\Models\ProductVariant;
use App\Repositories\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    protected $model;

    public function __construct(Purchase $model)
    {
        $this->model = $model;
    }

    public function getPurchases(array $filters = [], string $status = 'all', int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->with(['supplier', 'creator']);
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('purchase_code', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('supplier', function($subQ) use ($filters) {
                      $subQ->where('name', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }
        
        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Apply payment status filter
        if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'all') {
            $query->where('payment_status', $filters['payment_status']);
        }
        
        // Apply date range filter
        if (!empty($filters['from_date'])) {
            $query->whereDate('purchase_date', '>=', $filters['from_date']);
        }
        
        if (!empty($filters['to_date'])) {
            $query->whereDate('purchase_date', '<=', $filters['to_date']);
        }
        
        // Apply supplier filter
        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }
        
        // Order by
        $orderBy = $filters['order_by'] ?? 'purchase_date';
        $orderDir = $filters['order_dir'] ?? 'desc';
        $query->orderBy($orderBy, $orderDir);
        
        return $query->paginate($perPage);
    }

    public function getPurchase(int $id)
    {
        return $this->model->with(['items.product', 'items.productVariant', 'supplier', 'creator'])
                          ->findOrFail($id);
    }

    public function getPurchaseByCode(string $code)
    {
        return $this->model->with(['items.product', 'items.productVariant', 'supplier'])
                          ->where('purchase_code', $code)
                          ->firstOrFail();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        
        try {
            // Create purchase
            $purchase = $this->model->create([
                'purchase_code' => $data['purchase_code'] ?? Purchase::generatePurchaseCode(),
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'transport' => $data['transport'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'payment_status' => $data['payment_status'] ?? Purchase::PAYMENT_UNPAID,
                'status' => $data['status'] ?? Purchase::STATUS_PENDING,
                'user_id' => $data['user_id'],
            ]);
            
            // Create purchase items
            foreach ($data['items'] as $item) {
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => ProductVariant::find($item['product_variant_id'])->product_id,
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'received_qty' => $item['received_qty'],
                    'unit_price' => $item['unit_price'],
                ]);
                
                $purchaseItem->total = $purchaseItem->received_qty * $purchaseItem->unit_price;
                $purchaseItem->save();
            }
            
            // Calculate totals
            $purchase->sub_total = $purchase->items->sum('total');
            $purchase->total_amount = $purchase->sub_total 
                                    + ($purchase->transport ?? 0) 
                                    - $purchase->discount 
                                    + $purchase->tax;
            $purchase->save();
            
            // If status is delivered, update stock
            if ($purchase->status == Purchase::STATUS_DELIVERED) {
                $this->updateStock($purchase);
            }
            
            DB::commit();
            
            return $purchase->load(['items.product', 'items.productVariant']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(int $id, array $data)
    {
        DB::beginTransaction();
        
        try {
            $purchase = $this->getPurchase($id);
            
            // Only pending purchases can be updated
            if ($purchase->status == Purchase::STATUS_DELIVERED) {
                throw new \Exception('Delivered purchases cannot be updated.');
            }
            
            // Update purchase
            $purchase->update([
                'supplier_id' => $data['supplier_id'],
                'purchase_date' => $data['purchase_date'],
                'transport' => $data['transport'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'tax' => $data['tax'] ?? 0,
                'notes' => $data['notes'] ?? null,
                'payment_status' => $data['payment_status'] ?? $purchase->payment_status,
            ]);
            
            // Delete existing items
            $purchase->items()->delete();
            
            // Create new items
            foreach ($data['items'] as $item) {
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => ProductVariant::find($item['product_variant_id'])->product_id,
                    'product_variant_id' => $item['product_variant_id'] ?? null,
                    'received_qty' => $item['received_qty'],
                    'unit_price' => $item['unit_price'],
                ]);
                
                $purchaseItem->total = $purchaseItem->received_qty * $purchaseItem->unit_price;
                $purchaseItem->save();
            }
            
            // Recalculate totals
            $purchase->sub_total = $purchase->items->sum('total');
            $purchase->total_amount = $purchase->sub_total 
                                    + ($purchase->transport ?? 0) 
                                    - $purchase->discount 
                                    + $purchase->tax;
            $purchase->save();
            
            DB::commit();
            
            return $purchase->load(['items.product', 'items.productVariant']);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function delete(int $id)
    {
        $purchase = $this->getPurchase($id);
        
        // Only pending purchases can be deleted
        if ($purchase->status == Purchase::STATUS_DELIVERED) {
            throw new \Exception('Delivered purchases cannot be deleted.');
        }
        
        return $purchase->delete();
    }

    public function updateStatus(int $id, int $status)
    {
        DB::beginTransaction();
        
        try {
            $purchase = $this->getPurchase($id);
            
            if ($status == Purchase::STATUS_DELIVERED && $purchase->status != Purchase::STATUS_DELIVERED) {
                // Update stock when marking as delivered
                $this->updateStock($purchase);
            }
            
            $purchase->status = $status;
            $purchase->save();
            
            DB::commit();
            
            return $purchase;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updatePaymentStatus(int $id, int $paymentStatus)
    {
        $purchase = $this->getPurchase($id);
        $purchase->payment_status = $paymentStatus;
        $purchase->save();
        
        return $purchase;
    }

    public function getPurchaseStatistics(): array
    {
        return [
            'total_purchases' => $this->model->sum('total_amount'),
            'pending_delivery' => $this->model->where('status', Purchase::STATUS_PENDING)->count(),
            'this_month_purchases' => $this->model->whereMonth('purchase_date', now()->month)->sum('total_amount'),
        ];
    }

    protected function updateStock(Purchase $purchase)
    {
        foreach ($purchase->items as $item) {
            $variant = $item->productVariant;
            if ($variant) {
                $variant->qty += $item->received_qty;
                $variant->save();
            }
        }
    }
}