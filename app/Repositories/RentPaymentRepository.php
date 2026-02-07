<?php

namespace App\Repositories;

use App\Models\Backend\RentPayment;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\RentPaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class RentPaymentRepository extends BaseRepository implements RentPaymentRepositoryInterface
{
    public function __construct(RentPayment $model)
    {
        parent::__construct($model);
    }

    /**
     * Find all payments with filters and sorting
     */
    public function findAll(array $filters = [], string $orderBy = 'payment_date', string $orderDir = 'desc'): Builder
    {
        $query = $this->model->with(['rent.customer']);

        // Handle search across multiple fields
        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('rent', function ($q2) use ($search) {
                    $q2->where('rent_code', 'like', "%{$search}%")
                        ->orWhereHas('customer', function ($q3) use ($search) {
                            $q3->where('name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%");
                        });
                })
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('payment_for', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        // Handle payment method filter
        if (isset($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        // Handle rent filter
        if (isset($filters['rent_id'])) {
            $query->where('rent_id', $filters['rent_id']);
        }

        // Handle customer filter
        if (isset($filters['customer_id'])) {
            $query->whereHas('rent', function ($q) use ($filters) {
                $q->where('customer_id', $filters['customer_id']);
            });
        }

        // Handle single date filter
        if (isset($filters['payment_date'])) {
            $query->whereDate('payment_date', $filters['payment_date']);
        }

        // Handle date range filter
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('payment_date', [$filters['start_date'], $filters['end_date']]);
        }

        // Handle payment for filter
        if (isset($filters['payment_for'])) {
            $query->where('payment_for', $filters['payment_for']);
        }

        return $query->orderBy($orderBy, $orderDir);
    }

    /**
     * Get payment statistics
     */
    public function getStatistics(): array
    {
        $today = now()->format('Y-m-d');
        $thisMonthStart = now()->startOfMonth()->format('Y-m-d');
        $thisMonthEnd = now()->endOfMonth()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        // Get total statistics
        $totalStats = $this->model->select([
            DB::raw('COUNT(*) as total_payments'),
            DB::raw('SUM(amount) as total_amount'),
        ])->first();

        // Get today's statistics
        $todayStats = $this->model->select([
            DB::raw('COUNT(*) as today_count'),
            DB::raw('SUM(amount) as today_amount'),
        ])->whereDate('payment_date', $today)->first();

        // Get this month's statistics
        $monthStats = $this->model->select([
            DB::raw('COUNT(*) as month_count'),
            DB::raw('SUM(amount) as month_amount'),
        ])->whereBetween('payment_date', [$thisMonthStart, $thisMonthEnd])->first();

        return [
            'total_payments' => $totalStats->total_payments ?? 0,
            'total_amount' => $totalStats->total_amount ?? 0,
            'today_count' => $todayStats->today_count ?? 0,
            'today_amount' => $todayStats->today_amount ?? 0,
            'this_month_count' => $monthStats->month_count ?? 0,
            'this_month_amount' => $monthStats->month_amount ?? 0,
        ];
    }

    /**
     * Create payment
     */
    public function createPayment(int $rentId, array $data): RentPayment
    {
        return $this->model->create(array_merge($data, [
            'rent_id' => $rentId
        ]));
    }

    /**
     * Get payments for a rent
     */
    public function getByRentId(int $rentId): Collection
    {
        return $this->model->where('rent_id', $rentId)
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    /**
     * Get total paid amount for rent
     */
    public function getTotalPaid(int $rentId): float
    {
        return $this->model->where('rent_id', $rentId)
            ->sum('amount');
    }

    /**
     * Get last payment for rent
     */
    public function getLastPayment(int $rentId): ?RentPayment
    {
        return $this->model->where('rent_id', $rentId)
            ->orderBy('payment_date', 'desc')
            ->first();
    }

    /**
     * Get payments by date range
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->model->with('rent.customer')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    /**
     * Get payments by payment method
     */
    public function getByPaymentMethod(string $method): Collection
    {
        return $this->model->with('rent.customer')
            ->where('payment_method', $method)
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    /**
     * Get monthly payment summary
     */
    public function getMonthlySummary(int $year, int $month): array
    {
        $startDate = date('Y-m-01', strtotime("$year-$month-01"));
        $endDate = date('Y-m-t', strtotime("$year-$month-01"));

        $summary = $this->model->select([
            DB::raw('SUM(amount) as total_amount'),
            DB::raw('COUNT(*) as payment_count'),
            'payment_method'
        ])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        return [
            'total_amount' => $summary->sum('total_amount'),
            'payment_count' => $summary->sum('payment_count'),
            'by_method' => $summary->toArray(),
            'month' => $month,
            'year' => $year
        ];
    }

    /**
     * Get overdue payments
     */
    public function getOverduePayments(): Collection
    {
        // Implement based on your business logic for overdue payments
        // This is an example
        return $this->model->with('rent.customer')
            ->whereHas('rent', function ($query) {
                $query->where('total_due', '>', 0)
                    ->where('status', '!=', 'completed')
                    ->where('rent_date', '<', now()->subDays(30)); // Over 30 days old
            })
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    public function getTotalPaymentByRentId($rentId): float
    {
        return $this->model
        ->where('rent_id', $rentId)
        ->where(function ($q) {
            $q->whereNull('payment_for')
              ->orWhere('payment_for', '!=', 'deposit');
        })
        ->sum('amount');
    }
}
