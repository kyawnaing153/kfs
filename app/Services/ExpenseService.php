<?php

namespace App\Services;

use App\Repositories\ExpenseRepository;
use App\Models\Backend\Expense;
use Illuminate\Support\Facades\DB;

class ExpenseService
{
    protected $repo;

    public function __construct(ExpenseRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getExpenses($filters = [], $orderBy = 'id', $orderDir = 'desc')
    {
        return $this->repo->findAll($filters, $orderBy, $orderDir);
    }

    public function getExpense($id)
    {
        return $this->repo->findById($id);
    }

    public function createExpense($data)
    {
        return $this->repo->create($data);
    }

    public function updateExpense($id, $data)
    {
        return $this->repo->update($id, $data);
    }

    public function deleteExpense($id)
    {
        return $this->repo->delete($id);
    }

    public function toggleExpenseStatus($id)
    {
        return $this->repo->toggleStatus($id);
    }

    public function getExpenseStats(): array
    {
        $now = now();

        // Single query for most stats
        $stats = Expense::where('status', 1)->selectRaw("
        SUM(amount) as total_expenses")->first();

        // This month
        $thisMonthExpenses = Expense::where('status', 1)->whereYear('expense_date', $now->year)
            ->whereMonth('expense_date', $now->month)
            ->sum('amount');

        // Last month
        $lastMonth = $now->copy()->subMonth();
        $lastMonthExpenses = Expense::where('status', 1)->whereYear('expense_date', $lastMonth->year)
            ->whereMonth('expense_date', $lastMonth->month)
            ->sum('amount');

        // Optimized chart (GROUP BY instead of loop queries)
        $chartRaw = Expense::where('status', 1)->selectRaw("
            DATE_FORMAT(expense_date, '%Y-%m') as month,
            SUM(amount) as total
        ")
            ->where('expense_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Format chart
        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key = $month->format('Y-m');

            $chartLabels[] = $month->format('M Y');
            $chartData[] = $chartRaw->firstWhere('month', $key)->total ?? 0;
        }

        return [
            'totalExpenses'      => $stats->total_expenses ?? 0,
            'thisMonthExpenses'  => $thisMonthExpenses,
            'lastMonthExpenses'  => $lastMonthExpenses,
            'chartLabels'        => $chartLabels,
            'chartData'          => $chartData,
        ];
    }
}
