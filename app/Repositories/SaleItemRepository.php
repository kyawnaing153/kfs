<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SaleItemRepositoryInterface;
use App\Models\Backend\SaleItem;
use Illuminate\Database\Eloquent\Collection;

class SaleItemRepository implements SaleItemRepositoryInterface
{
    public function getItemsBySaleId(string $saleId): Collection
    {
        return SaleItem::with('productVariant.product')
            ->where('sale_id', $saleId)
            ->get();
    }

    public function addItemToSale(array $data): SaleItem
    {
        return SaleItem::create($data);
    }

    public function updateItem(string $id, array $data): bool
    {
        $item = SaleItem::find($id);
        if (!$item) {
            return false;
        }

        return $item->update($data);
    }

    public function deleteItem(string $id): bool
    {
        $item = SaleItem::find($id);
        if (!$item) {
            return false;
        }

        return $item->delete();
    }

    public function deleteItemsBySaleId(string $saleId): bool
    {
        return SaleItem::where('sale_id', $saleId)->delete();
    }

    public function getAllSaleItems(array $filters = []): Collection
    {
        $query = SaleItem::with([
            'sale' => function ($query) {
                $query->select('id', 'sale_code', 'customer_id', 'sale_date', 'status');
            },
            'sale.customer' => function ($query) {
                $query->select('id', 'name', 'phone_number');
            },
            'productVariant' => function ($query) {
                $query->select('id', 'product_id', 'size', 'qty', 'unit');
            },
            'productVariant.product' => function ($query) {
                $query->select('id', 'product_name');
            }
        ]);

        if (!empty($filters['sale_code'])) {
            $query->whereHas('sale', function ($q) use ($filters) {
                $q->where('sale_code', 'like', '%' . $filters['sale_code'] . '%');
            });
        }

        if (!empty($filters['customer_id'])) {
            $query->whereHas('sale', function ($q) use ($filters) {
                $q->where('customer_id', $filters['customer_id']);
            });
        }

        if (!empty($filters['product_name'])) {
            $query->whereHas('productVariant.product', function ($q) use ($filters) {
                $q->where('product_name', 'like', '%' . $filters['product_name'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->whereHas('sale', function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereHas('sale', function ($q) use ($filters) {
                $q->where('sale_date', '>=', $filters['date_from']);
            });
        }

        if (!empty($filters['date_to'])) {
            $query->whereHas('sale', function ($q) use ($filters) {
                $q->where('sale_date', '<=', $filters['date_to']);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}