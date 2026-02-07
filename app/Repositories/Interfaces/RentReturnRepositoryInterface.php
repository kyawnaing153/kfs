<?php

namespace App\Repositories\Interfaces;

use App\Models\Backend\RentReturn;
use Illuminate\Database\Eloquent\Collection;

interface RentReturnRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all returns
     */
    public function getAll(array $filters = [], string $orderBy = 'return_date', string $orderDir = 'desc'): Collection;

    /**
     * Create return with items
     */
    public function createWithItems(int $rentId, array $returnData, array $items): RentReturn;

    /**
     * Get returns for a rent
     */
    public function getByRentId(int $rentId): Collection;

    /**
     * Get returns with items
     */
    public function getReturnsWithItems(array $rentIds = []): Collection;

    /**
     * Get returns by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get total refund amount for a rent
     */
    public function getTotalRefundAmount(int $rentId): float;

    /**
     * Get total collect amount for a rent
     */
    public function getTotalCollectAmount(int $rentId): float;
}