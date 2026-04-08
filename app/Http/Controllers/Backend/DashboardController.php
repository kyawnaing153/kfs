<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $dashboardData = $this->dashboardService->getDashboardData();
        $quickStats = $this->dashboardService->getQuickStats();
        #dd($dashboardData);
        return view('pages.admin.dashboard.dashboard', compact('dashboardData', 'quickStats'));
    }

    public function getChartData(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        $salesData = $this->dashboardService->getMonthlySalesData($year);
        $expensesData = $this->dashboardService->getMonthlyExpensesData($year);
        
        return response()->json([
            'success' => true,
            'data' => [
                'sales' => array_values($salesData),
                'expenses' => array_values($expensesData),
                'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            ]
        ]);
    }
}