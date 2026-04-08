<?php

namespace App\Services;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Models\Backend\{Sale, Expense, Rent};
use Carbon\Carbon;

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
        $monthlyRents = $this->dashboardRepository->getMonthlyRentsData();

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
            'low_stock_products' => $this->dashboardRepository->getLowStockProducts(5),
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
}