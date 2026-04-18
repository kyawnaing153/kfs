<?php

namespace App\Services;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Models\Backend\{Sale, Expense, Rent};
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData(): array
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        $monthlySales = $this->dashboardRepository->getMonthlySalesData($currentYear);
        $monthlyExpenses = $this->dashboardRepository->getMonthlyExpensesData($currentYear);
        $monthlyRents = $this->dashboardRepository->getMonthlyRentsData($currentYear);

        $netProfit = $this->dashboardRepository->getTotalSales() + 
                     $this->dashboardRepository->getTotalRents() - 
                     $this->dashboardRepository->getTotalExpenses();

        return [
            'summary' => [
                'total_sales' => $this->dashboardRepository->getTotalSales(), //del
                'total_expenses' => $this->dashboardRepository->getTotalExpenses(),
                'total_rents' => $this->dashboardRepository->getTotalRents(),
                'total_customers' => $this->dashboardRepository->getTotalCustomers(),
                'total_products' => $this->dashboardRepository->getTotalProducts(),
                'net_profit' => $netProfit,
            ],
            'current_month_sales' => $monthlySales[$currentMonth] ?? 0,
            'current_month_expenses' => $monthlyExpenses[$currentMonth] ?? 0,
            'recent_activities' => [
                'sales' => $this->dashboardRepository->getRecentSales(5),
                'purchases' => $this->dashboardRepository->getRecentPurchases(5),
                'rents' => $this->dashboardRepository->getRecentRents(5),
                'expenses' => $this->dashboardRepository->getRecentExpenses(5),
            ],
            'payment_summary' => $this->dashboardRepository->getPaymentStatusSummary(),
            'charts' => [
                'sales_data' => array_values($monthlySales),
                'expenses_data' => array_values($monthlyExpenses),
                'rents_data' => array_values($monthlyRents),
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ],
            'top_products' => $this->dashboardRepository->getTopProducts(5),
            'low_stock_products' => $this->dashboardRepository->getLowStockProducts(5) ?? [],
            'outstanding_summary' => [
                'rent_due' => $this->dashboardRepository->getRentOutstandingPayments(),
                'sale_due' => $this->dashboardRepository->getSaleOutstandingPayments(),
            ],
        ];
    }

    public function getQuickStats(): array
    {
        return [
            'today_rents' => Rent::whereDate('rent_date', Carbon::today())->sum('sub_total'),
            'today_sales' => Sale::whereDate('sale_date', Carbon::today())->sum('total'),
            'today_expenses' => Expense::whereDate('expense_date', Carbon::today())
                ->where('status', 1)
                ->sum('amount'),
            'pending_rents' => Rent::where('status', 'pending')->count(),
            'pending_sales' => Sale::where('status', 'pending')->count(),
        ];
    }

    public function getFinancialChartData(string $period = 'year', ?int $year = null): array
    {
        $period = strtolower($period);
        $now = Carbon::now();

        if (!in_array($period, ['week', 'month', 'year'], true)) {
            $period = 'year';
        }

        if ($period === 'week') {
            $weekStart = $now->copy()->startOfWeek(Carbon::MONDAY);
            return $this->buildRangeChartData(
                $weekStart,
                $weekStart->copy()->addDays(6),
                'D'
            );
        }

        if ($period === 'month') {
            return $this->buildRangeChartData(
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
                'j'
            );
        }

        $selectedYear = $year ?? $now->year;
        $salesData = array_values($this->dashboardRepository->getMonthlySalesData($selectedYear));
        $expensesData = array_values($this->dashboardRepository->getMonthlyExpensesData($selectedYear));
        $rentsData = array_values($this->dashboardRepository->getMonthlyRentsData($selectedYear));

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'sales' => $salesData,
            'expenses' => $expensesData,
            'rents' => $rentsData,
        ];
    }

    private function buildRangeChartData(Carbon $startDate, Carbon $endDate, string $labelFormat): array
    {
        $salesByDate = $this->dashboardRepository->getSalesByDateRange($startDate, $endDate);
        $expensesByDate = $this->dashboardRepository->getExpensesByDateRange($startDate, $endDate);
        $rentsByDate = $this->dashboardRepository->getRentsByDateRange($startDate, $endDate);

        $labels = [];
        $sales = [];
        $expenses = [];
        $rents = [];

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $dateKey = $date->toDateString();
            $labels[] = $date->format($labelFormat);
            $sales[] = (float) ($salesByDate[$dateKey] ?? 0);
            $expenses[] = (float) ($expensesByDate[$dateKey] ?? 0);
            $rents[] = (float) ($rentsByDate[$dateKey] ?? 0);
        }

        return [
            'labels' => $labels,
            'sales' => $sales,
            'expenses' => $expenses,
            'rents' => $rents,
        ];
    }
}
