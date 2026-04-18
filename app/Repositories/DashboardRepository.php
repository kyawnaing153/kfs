<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Models\Backend\Sale;
use App\Models\Backend\Purchase;
use App\Models\Backend\Expense;
use App\Models\Backend\Rent;
use App\Models\Customer;
use App\Models\{Product, ProductVariant};
use App\Models\Backend\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getTotalSales(): float
    {
        return Sale::where('status', 'completed')->sum('sub_total');
    }

    public function getTotalExpenses(): float
    {
        return Expense::where('status', 1)->sum('amount');
    }

    public function getTotalRents(): float
    {
        return Rent::where('status', 'ongoing')->sum('sub_total');
    }

    public function getTotalCustomers(): int
    {
        return Customer::where('status', 1)->count();
    }

    public function getTotalProducts(): int
    {
        return Product::where('status', 1)->count();
    }

    public function getRecentSales(int $limit = 5): array
    {
        return Sale::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getRecentPurchases(int $limit = 5): array
    {
        return Purchase::with('supplier')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getRecentRents(int $limit = 5): array
    {
        return Rent::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getRecentExpenses(int $limit = 5): array
    {
        return Expense::where('status', 1)
            ->orderBy('expense_date', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getMonthlySalesData(int $year): array
    {
        $data = Sale::select(
            DB::raw('MONTH(sale_date) as month'),
            DB::raw('SUM(sub_total) as total')
        )
            ->whereYear('sale_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        $result = array_fill(1, 12, 0);
        foreach ($data as $month => $value) {
            $result[$month] = $value['total'];
        }

        return $result;
    }

    public function getMonthlyRentsData(int $year): array
    {
        $data = Rent::select(
            DB::raw('MONTH(rent_date) as month'),
            DB::raw('SUM(sub_total) as total')
        )
            ->whereYear('rent_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        $result = array_fill(1, 12, 0);
        foreach ($data as $month => $value) {
            $result[$month] = $value['total'];
        }

        return $result;
    }

    public function getSalesByDateRange(Carbon $startDate, Carbon $endDate): array
    {
        return Sale::select(
            DB::raw('DATE(sale_date) as period'),
            DB::raw('SUM(total) as total')
        )
            ->whereBetween('sale_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period')
            ->toArray();
    }

    public function getExpensesByDateRange(Carbon $startDate, Carbon $endDate): array
    {
        return Expense::select(
            DB::raw('DATE(expense_date) as period'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 1)
            ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period')
            ->toArray();
    }

    public function getRentsByDateRange(Carbon $startDate, Carbon $endDate): array
    {
        return Rent::select(
            DB::raw('DATE(rent_date) as period'),
            DB::raw('SUM(sub_total) as total')
        )
            ->whereBetween('rent_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('period')
            ->orderBy('period')
            ->pluck('total', 'period')
            ->toArray();
    }

    public function getMonthlyExpensesData(int $year): array
    {
        $data = Expense::select(
            DB::raw('MONTH(expense_date) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 1)
            ->whereYear('expense_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->toArray();

        $result = array_fill(1, 12, 0);
        foreach ($data as $month => $value) {
            $result[$month] = $value['total'];
        }

        return $result;
    }

    public function getPaymentStatusSummary(): array
    {
        $salesDue = Sale::where('total_due', '>', 0)->sum('total_due');
        $rentsDue = Rent::where('total_due', '>', 0)->sum('total_due');

        $purchasesUnpaid = Purchase::where('payment_status', Purchase::PAYMENT_UNPAID)
            ->sum('total_amount');

        return [
            'sales_due' => $salesDue,
            'rents_due' => $rentsDue,
            'purchases_unpaid' => $purchasesUnpaid,
            'total_outstanding' => $salesDue + $rentsDue + $purchasesUnpaid,
        ];
    }

    public function getTopProducts(int $limit = 5): array
    {
        return SaleItem::select(
            'product_variant_id',
            DB::raw('SUM(sale_qty) as total_quantity'),
            DB::raw('SUM(total) as total_amount')
        )
            ->with(['productVariant.product']) // Load variant and its product
            ->whereNotNull('product_variant_id') // Ensure we have variant ID
            ->groupBy('product_variant_id')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($saleItem) {
                return [
                    'product_variant_id' => $saleItem->product_variant_id,
                    'product_name' => $saleItem->productVariant->product->product_name ?? 'N/A',
                    'variant_size' => $saleItem->productVariant->size ?? 'N/A',
                    'variant_unit' => $saleItem->productVariant->unit ?? 'N/A',
                    'sku' => $saleItem->productVariant->sku ?? 'N/A',
                    'total_quantity' => $saleItem->total_quantity,
                    'total_amount' => $saleItem->total_amount,
                ];
            })
            ->toArray();
    }

    public function getLowStockProducts(int $limit = 5, int $threshold = 10): array
    {
        // Get variants with low stock along with their product info
        $lowStockVariants = ProductVariant::with('product')
            ->where('qty', '<=', $threshold)
            ->orderBy('qty', 'asc')
            ->limit($limit)
            ->get()
            ->groupBy('product_id')
            ->map(function ($variant) {
                return [
                    'product_id' => $variant->product_id,
                    'product_name' => $variant->product->product_name ?? 'N/A',
                    'variant_id' => $variant->id,
                    'size' => $variant->size,
                    'unit' => $variant->unit,
                    'current_stock' => $variant->qty,
                    'sku' => $variant->sku,
                    'status' => $variant->qty <= 0 ? 'Out of Stock' : ($variant->qty <= 5 ? 'Critical Stock' : 'Low Stock'),
                ];
            })
            ->toArray();

        return $lowStockVariants;
    }

    public function getRentOutstandingPayments(): float
    {
        return Rent::where('total_due', '>', 0)->sum('total_due');
    }

    public function getSaleOutstandingPayments(): float
    {
        return Sale::where('total_due', '>', 0)->sum('total_due');
    }
}
