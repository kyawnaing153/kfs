<?php

namespace App\Repositories\Interfaces;

use App\Models\Backend\Rent;
use Illuminate\Database\Eloquent\Collection;

interface RentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get rents by status
     */
    public function getByStatus(array $filters = [], string $status = 'all', int $perPage = 20): Collection;

    /**
     * Create rent with items
     */
    public function createWithItems(array $rentData, array $items): Rent;

    /**
     * Update rent with items
     */
    public function updateWithItems(Rent $rent, array $rentData, array $items): Rent;

    /**
     * Get rent by ID with all relations
     */
    public function getFullDetails(int $id): ?Rent;

    /**
     * Generate unique rent code
     */
    public function generateRentCode(): string;

    /**
     * Get rents with due payments
     */
    public function getRentsWithDuePayments(): Collection;

    /**
     * Get rents by customer ID
     */
    public function getByCustomerId(int $customerId): Collection;

    /**
     * Get rents with overdue items
     */
    public function getRentsWithOverdueItems(): Collection;
}