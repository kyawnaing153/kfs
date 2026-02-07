<?php

namespace App\Repositories\Interfaces;

use App\Models\Backend\RentPayment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface RentPaymentRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Find all payments with filters and sorting
     */
    public function findAll(array $filters = [], string $orderBy = 'payment_date', string $orderDir = 'desc'): Builder;

    /**
     * Get payment statistics
     */
    public function getStatistics(): array;
    
    /**
     * Create payment
     */
    public function createPayment(int $rentId, array $data): RentPayment;

    /**
     * Get payments for a rent
     */
    public function getByRentId(int $rentId): Collection;

    /**
     * Get total paid amount for rent
     */
    public function getTotalPaid(int $rentId): float;

    /**
     * Get last payment for rent
     */
    public function getLastPayment(int $rentId): ?RentPayment;

    /**
     * Get payments by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection;

    /**
     * Get payments by payment method
     */
    public function getByPaymentMethod(string $method): Collection;

    /**
     * Get monthly payment summary
     */
    public function getMonthlySummary(int $year, int $month): array;

    /**
     * Get overdue payments
     */
    public function getOverduePayments(): Collection;

    public function getTotalPaymentByRentId(int $rentId): float;
}