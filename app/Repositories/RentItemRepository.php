<?php

namespace App\Repositories;

use App\Models\Backend\RentItem;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RentItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RentItemRepository extends BaseRepository implements RentItemRepositoryInterface
{
    public function __construct(RentItem $model)
    {
        parent::__construct($model);
    }

    /**
     * Get items for a rent
     */
    public function getByRentId(int $rentId): Collection
    {
        return $this->model->with('productVariant')
            ->where('rent_id', $rentId)
            ->get();
    }

    /**
     * Find items
     */
    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Get items with remaining quantity to return
     */
    public function getItemsWithRemainingQuantity(int $rentId): Collection
    {
        return $this->model->with('productVariant')
            ->where('rent_id', $rentId)
            ->whereRaw('rent_qty > returned_qty')
            ->get();
    }

    /**
     * Update returned quantity
     */
    public function updateReturnedQuantity(int $itemId, int $quantity): void
    {
        $item = $this->model->findOrFail($itemId);
        $item->increment('returned_qty', $quantity);
    }

    /**
     * Check if all items are fully returned
     */
    public function areAllItemsReturned(int $rentId): bool
    {
        return !$this->model->where('rent_id', $rentId)
            ->whereRaw('rent_qty > returned_qty')
            ->exists();
    }

    /**
     * Get total quantity rented for a variant
     */
    public function getTotalRentedQuantity(int $productVariantId): int
    {
        return $this->model->where('product_variant_id', $productVariantId)
            ->whereHas('rent', function ($query) {
                $query->whereIn('status', ['pending', 'ongoing']);
            })
            ->sum('rent_qty');
    }

    /**
     * Get items due for return by date
     */
    public function getItemsDueForReturn(string $date): Collection
    {
        // Implement based on your business logic
        // This is an example - adjust according to your return policy
        return $this->model->with(['rent.customer', 'productVariant'])
            ->whereHas('rent', function ($query) use ($date) {
                $query->where('rent_date', '<=', $date)
                    ->whereIn('status', ['pending', 'ongoing']);
            })
            ->whereRaw('rent_qty > returned_qty')
            ->get();
    }

    /**
     * Get all rent items
     */
    public function getAllRentItems(array $filters = []): Collection
    {
        $query = $this->model->with([
            'rent' => function ($query) {
                $query->select('id', 'rent_code', 'customer_id', 'rent_date', 'status');
            },
            'rent.customer' => function ($query) {
                $query->select('id', 'name', 'phone_number');
            },
            'productVariant' => function ($query) {
                $query->select('id', 'product_id', 'size', 'qty', 'unit');
            },
            'productVariant.product' => function ($query) {
                $query->select('id', 'product_name');
            }
        ]);

        // Apply filters
        if (!empty($filters['rent_code'])) {
            $query->whereHas('rent', function ($q) use ($filters) {
                $q->where('rent_code', 'like', '%' . $filters['rent_code'] . '%');
            });
        }

        if (!empty($filters['customer_id'])) {
            $query->whereHas('rent', function ($q) use ($filters) {
                $q->where('customer_id', $filters['customer_id']);
            });
        }

        if (!empty($filters['product_name'])) {
            $query->whereHas('productVariant.product', function ($q) use ($filters) {
                $q->where('product_name', 'like', '%' . $filters['product_name'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->whereHas('rent', function ($q) use ($filters) {
                $q->where('status', $filters['status']);
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereHas('rent', function ($q) use ($filters) {
                $q->where('rent_date', '>=', $filters['date_from']);
            });
        }

        if (!empty($filters['date_to'])) {
            $query->whereHas('rent', function ($q) use ($filters) {
                $q->where('rent_date', '<=', $filters['date_to']);
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
