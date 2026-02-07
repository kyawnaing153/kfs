<?php

namespace App\Repositories;

use App\Models\Backend\RentReturn;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RentReturnRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RentReturnRepository extends BaseRepository implements RentReturnRepositoryInterface
{
    public function __construct(RentReturn $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all returns
     */
    public function getAll(array $filters = [], string $status = 'all', string $orderBy = 'return_date', string $orderDir = 'desc'): Collection
    {
        $query = $this->model->with(['rent.customer', 'items']);
        
        if($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Apply filters
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('rent', function($q2) use ($search) {
                    $q2->where('rent_code', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q3) use ($search) {
                          $q3->where('name', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                      });
                });
            });
        }
        
        return $query->orderBy($orderBy, $orderDir)->get();
    }

    /**
     * Create return with items
     */
    public function createWithItems(int $rentId, array $returnData, array $items): RentReturn
    {
        $return = $this->model->create(array_merge($returnData, [
            'rent_id' => $rentId
        ]));
        
        foreach ($items as $item) {
            $return->items()->create($item);
        }
        
        return $return->load('items');
    }

    /**
     * Get returns for a rent
     */
    public function getByRentId(int $rentId): Collection
    {
        return $this->model->with('items.rentItem')
            ->where('rent_id', $rentId)
            ->orderBy('return_date', 'desc')
            ->get();
    }

    /**
     * Get returns with items
     */
    public function getReturnsWithItems(array $rentIds = []): Collection
    {
        $query = $this->model->with(['rent.customer', 'items.rentItem.productVariant']);
        
        if (!empty($rentIds)) {
            $query->whereIn('rent_id', $rentIds);
        }
        
        return $query->orderBy('return_date', 'desc')->get();
    }

    /**
     * Get returns by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->with(['rent.customer', 'items'])
            ->whereBetween('return_date', [$startDate, $endDate])
            ->orderBy('return_date', 'desc')
            ->get();
    }

    /**
     * Get total refund amount for a rent
     */
    public function getTotalRefundAmount(int $rentId): float
    {
        return $this->model->where('rent_id', $rentId)
            ->sum('refund_amount');
    }

    /**
     * Get total collect amount for a rent
     */
    public function getTotalCollectAmount(int $rentId): float
    {
        return $this->model->where('rent_id', $rentId)
            ->sum('collect_amount');
    }
}