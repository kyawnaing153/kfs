<?php

namespace App\Repositories;

use App\Models\Backend\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function findById(int $id)
    {
        return Expense::find($id);
    }

    public function findAll(array $filters = [], string $orderBy = 'id', string $orderDir = 'desc')
    {
        return Expense::query()
        ->when($filters['status'] ?? null, fn($q, $status) => $q->status($status))
        ->when($filters['search'] ?? null, fn($q, $search) => $q->search($search))
            ->orderBy($orderBy, $orderDir);
    }

    public function create(array $data)
    {
        return Expense::create($data);
    }

    public function update(int $id, array $data): Expense
    {
        $expense = Expense::findOrFail($id);
        $expense->update($data);

        return $expense;
    }

    public function delete(int $id): bool
    {
        return Expense::findOrFail($id)->delete();
    }

    public function toggleStatus(int $id): Expense
    {
        Expense::where('id', $id)->update([
            'status' => DB::raw('NOT status')
        ]);

        return Expense::findOrFail($id);
    }
}
