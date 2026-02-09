<?php

namespace App\Repositories;

use App\Models\Backend\Rent;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RentRepository extends BaseRepository implements RentRepositoryInterface
{
    public function __construct(Rent $model)
    {
        parent::__construct($model);
    }

    /**
     * Get rents by status
     */
    public function getByStatus(array $filters = [], string $status = 'all', int $perPage = 20): Collection
    {
        $query = $this->model->with(['customer', 'items.productVariant']);

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('rent_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($status === 'all') {
            //$query->where('status', $status);
            $query->where('status', '!=', 'completed');
        } else {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create rent with items
     */
    public function createWithItems(array $rentData, array $items): Rent
    {
        $rent = $this->model->create($rentData);

        foreach ($items as $item) {
            $rent->items()->create($item);
        }

        return $rent->load('items');
    }

    /**
     * Update rent with items
     */
    public function updateWithItems(Rent $rent, array $rentData, array $items): Rent
    {
        $rent->update($rentData);

        // Delete existing items
        $rent->items()->delete();

        // Create new items
        foreach ($items as $item) {
            $rent->items()->create($item);
        }

        return $rent->load('items');
    }

    /**
     * Get rent by ID with all relations
     */
    public function getFullDetails(int $id): ?Rent
    {
        return $this->model->with([
            'customer',
            'items.productVariant.product',
            'payments',
            'returns.items.rentItem'
        ])->find($id);
    }

    /**
     * Generate unique rent code
     */
    public function generateRentCode(): string
    {
        $prefix = 'RENT-' . date('Ym');
        $lastRent = $this->model->where('rent_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRent) {
            $lastNumber = (int) substr($lastRent->rent_code, -2);
            $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '01';
        }

        return $prefix . '-' . $newNumber;
    }

    /**
     * Get rents with due payments
     */
    public function getRentsWithDuePayments(): Collection
    {
        return $this->model->with(['customer'])
            ->where('total_due', '>', 0)
            ->where('status', '!=', 'completed')
            ->orderBy('total_due', 'desc')
            ->get();
    }

    /**
     * Get rents by customer ID
     */
    public function getByCustomerId(int $customerId): Collection
    {
        return $this->model->with(['items', 'payments'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get rents with overdue items
     */
    public function getRentsWithOverdueItems(): Collection
    {
        // Implement based on your business logic for overdue items
        return $this->model->with(['customer', 'items'])
            ->where('status', 'ongoing')
            ->whereHas('items', function ($query) {
                // Example: Items rented more than 30 days ago
                $query->where('created_at', '<', now()->subDays(30));
            })
            ->get();
    }

    /**
     * Get all rent items for reporting
     */
    public function getAllRentItems(): Collection
    {
        return RentItem::with(['rent', 'productVariant.product'])->get();
    }
}
