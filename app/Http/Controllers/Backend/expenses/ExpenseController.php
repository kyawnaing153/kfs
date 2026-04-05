<?php

namespace App\Http\Controllers\Backend\expenses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Expense\ExpenseRequest;
use Illuminate\Http\Request;
use App\Services\ExpenseService;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        $orderBy = $request->input('order_by', 'id');
        $orderDir = $request->input('order_dir', 'desc');

        $expenses = $this->expenseService->getExpenses($filters, $orderBy, $orderDir)->paginate(15);

        $stats = $this->expenseService->getExpenseStats();

        return view('pages.admin.expenses.index', [
            'expenses' => $expenses,
            ...$stats // spread stats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseRequest $request)
    {
        $validatedData = $request->validatedData();
        $this->expenseService->createExpense($validatedData);
        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $expense = $this->expenseService->getExpense($id);
        return view('pages.admin.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $expense = $this->expenseService->getExpense($id);
        return view('pages.admin.expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseRequest $request, string $id)
    {
        $validatedData = $request->validatedData();
        $this->expenseService->updateExpense($id, $validatedData);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->expenseService->deleteExpense($id);
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    /*
    * Toggle expense status (Active/Inactive)
    */
    public function toggleStatus(string $id)
    {
        $this->expenseService->toggleExpenseStatus($id);
        return redirect()->route('expenses.index')->with('success', 'Expense status updated successfully.');
    }
}
