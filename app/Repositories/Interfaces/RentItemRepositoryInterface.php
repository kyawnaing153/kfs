<?php

namespace App\Repositories\Interfaces;

use App\Models\Backend\RentItem;
use Illuminate\Database\Eloquent\Collection;

interface RentItemRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get items for a rent
     */
    public function getByRentId(int $rentId): Collection;

    /**
     * Get items with remaining quantity to return
     */
    public function getItemsWithRemainingQuantity(int $rentId): Collection;

    /**
     * Update returned quantity
     */
    public function updateReturnedQuantity(int $itemId, int $quantity): void;

    /**
     * Check if all items are fully returned
     */
    public function areAllItemsReturned(int $rentId): bool;

    /**
     * Get total quantity rented for a variant
     */
    public function getTotalRentedQuantity(int $productVariantId): int;

    /**
     * Get items due for return by date
     */
    public function getItemsDueForReturn(string $date): Collection;

    /**
     * Get all rent items
     */
    public function getAllRentItems(): Collection;
}