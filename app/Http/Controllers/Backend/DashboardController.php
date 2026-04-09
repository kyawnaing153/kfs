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
        $validated = $request->validate([
            'period' => 'nullable|in:week,month,year',
            'year' => 'nullable|integer|min:2000|max:2100',
        ]);

        $period = $validated['period'] ?? 'year';
        $year = isset($validated['year']) ? (int) $validated['year'] : null;

        $chartData = $this->dashboardService->getFinancialChartData($period, $year);

        return response()->json([
            'success' => true,
            'data' => $chartData,
        ]);
    }
}
