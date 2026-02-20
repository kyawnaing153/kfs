<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SaleRepositoryInterface;
use App\Models\Backend\Sale;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleRepository implements SaleRepositoryInterface
{
    public function getAllSales(array $filters = []): LengthAwarePaginator
    {
        $query = Sale::with('customer')->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('sale_date', [$filters['start_date'], $filters['end_date']]);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('sale_code', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                    });
            });
        }

        return $query->paginate(15);
    }

    public function getSaleById(string $id): ?Sale
    {
        return Sale::with(['customer', 'items.productVariant.product'])->find($id);
    }

    public function createSale(array $data): Sale
    {
        DB::beginTransaction();
        try {
            $sale = Sale::create($data);
            DB::commit();
            return $sale;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateSale(string $id, array $data): bool
    {
        $sale = $this->getSaleById($id);
        if (!$sale) {
            return false;
        }

        DB::beginTransaction();
        try {
            $updated = $sale->update($data);
            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteSale(string $id): bool
    {
        $sale = $this->getSaleById($id);
        if (!$sale) {
            return false;
        }

        DB::beginTransaction();
        try {
            $sale->items()->delete();
            $deleted = $sale->delete();
            DB::commit();
            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSalesByStatus(string $status): LengthAwarePaginator
    {
        return Sale::with('customer')
            ->where('status', $status)
            ->latest()
            ->paginate(15);
    }

    public function getSalesByCustomer(string $customerId): LengthAwarePaginator
    {
        return Sale::with('customer')
            ->where('customer_id', $customerId)
            ->latest()
            ->paginate(15);
    }

    public function getSalesByDateRange(string $startDate, string $endDate): LengthAwarePaginator
    {
        return Sale::with('customer')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->latest()
            ->paginate(15);
    }

    public function generateSaleCode(): string
    {
        $prefix = 'SALE-' . date('Ym');
        $latestSale = Sale::where('sale_code', 'like', $prefix . '%')
            ->orderBy('sale_code', 'desc')
            ->first();

        if ($latestSale) {
            $lastNumber = (int) substr($latestSale->sale_code, -2);
            $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '01';
        }

        return $prefix . '-' . $newNumber;
    }

    public function getSaleStatictics($sales)
    {
        $thisYearSales = Sale::whereYear('sale_date', now()->year)->get();

        $totalSales = $thisYearSales->count();

        $pendingSales = $thisYearSales
            ->where('status', 'pending')
            ->count();

        $completedSales = $thisYearSales
            ->where('status', 'completed')
            ->count();

        $totalRevenue = $thisYearSales
            ->where('status', 'completed')
            ->sum('sub_total');

        return [
            'total_sales' => $totalSales,
            'pending_sales' => $pendingSales,
            'completed_sales' => $completedSales,
            'total_revenue' => $totalRevenue
        ];
    }
}
