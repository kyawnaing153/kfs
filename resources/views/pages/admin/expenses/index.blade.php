@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Expenses Dashboard" />

    <div class="space-y-6">
        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Expenses Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Expenses</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            Ks {{ number_format($totalExpenses ?? 0, 0) }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-600 dark:text-gray-400">All time total</span>
                </div>
            </div>

            <!-- This Month Expenses Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">This Month</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            Ks {{ number_format($thisMonthExpenses ?? 0, 0) }}
                        </p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @php
                        $monthlyChange = isset($lastMonthExpenses) && $lastMonthExpenses > 0 
                            ? (($thisMonthExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100 
                            : 0;
                    @endphp
                    @if($monthlyChange > 0)
                        <span class="text-red-600 dark:text-red-400">↑ {{ number_format($monthlyChange, 1) }}%</span>
                        <span class="ml-1 text-gray-600 dark:text-gray-400">from last month</span>
                    @elseif($monthlyChange < 0)
                        <span class="text-green-600 dark:text-green-400">↓ {{ number_format(abs($monthlyChange), 1) }}%</span>
                        <span class="ml-1 text-gray-600 dark:text-gray-400">from last month</span>
                    @else
                        <span class="text-gray-600 dark:text-gray-400">No change from last month</span>
                    @endif
                </div>
            </div>
            
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <!-- Monthly Expenses Chart -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Monthly Expenses Trend</h3>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Last 6 months</span>
                </div>
                <div class="h-64">
                    <canvas id="monthlyExpensesChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Top Expenses Card -->
        <div class="rounded-2xl border mb-8 border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-5 mb-4 sm:flex-row sm:items-center sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Expense Directory
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage your organization's expenses
                    </p>
                </div>

                <div class="ml-auto flex flex-col gap-2 w-full sm:w-auto sm:flex-row sm:items-center sm:gap-3">
                    <!-- Search with Cancel Icon -->
                    <div class="w-full sm:w-64">
                        <form method="GET" action="{{ route('expenses.index') }}" class="relative w-full">
                            @if (request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search expenses..."
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-4 pr-10 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                            @if (request('search'))
                                <a href="{{ route('expenses.index', array_filter([
                                    'status' => request('status'),
                                ])) }}"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                        </form>
                    </div>

                    <!-- Additional Filters -->
                    <div class="w-full flex flex-row gap-2 sm:w-auto">
                        <form method="GET" action="{{ route('expenses.index') }}" class="w-full sm:w-auto">
                            @if (request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <select name="status" onchange="this.form.submit()"
                                class="w-full h-[42px] rounded-lg border border-gray-300 bg-transparent px-3 text-sm
                                focus:border-blue-300 focus:ring-2 focus:ring-blue-500/10
                                dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>

                        @if (request()->hasAny(['search', 'status']))
                            <a href="{{ route('expenses.index') }}"
                                class="h-[42px] inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-3 text-sm font-medium
                                hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-white/[0.05]">
                                Clear
                            </a>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="w-full flex flex-row gap-2 sm:w-auto">
                        <a href="{{ route('expenses.create') }}"
                            class="flex-1 h-[42px] inline-flex items-center justify-center gap-2 rounded-lg px-3 text-sm font-medium
                            bg-blue-600 text-white hover:bg-blue-700 sm:flex-none sm:px-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Expense
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto px-5 mb-8">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expense Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expense Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse ($expenses as $expense)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $expense->expense_title }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $expense->note ? Str::limit($expense->note, 30) : '' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        Ks {{ number_format($expense->amount, 1) }}
                                    </div>
                                    @php
                                        $statusColors = [
                                            1 => 'bg-blue-50 text-blue-600 dark:bg-blue-800 dark:text-blue-100',
                                            0 => 'bg-red-50 text-red-600 dark:bg-red-900 dark:text-red-200',
                                        ];
                                        $statusText = $expense->status == 1 ? 'Active' : 'Inactive';
                                        $colorClass = $statusColors[$expense->status] ?? $statusColors[0];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $expense->expense_date }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <div class="relative inline-block text-left" x-data="{ open{{ $expense->id }}: false }">
                                        <button type="button"
                                            @click="open{{ $expense->id }} = !open{{ $expense->id }}"
                                            @click.away="open{{ $expense->id }} = false"
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <div x-show="open{{ $expense->id }}"
                                            x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-blue-500/10 dark:bg-gray-800"
                                            role="menu" style="display: none;">
                                            <div class="py-1" role="none">
                                                <form method="POST" action="{{ route('expenses.toggle-status', $expense->id) }}" class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                        <svg class="h-4 w-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                        {{ $expense->status == 1 ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>

                                                <a href="{{ route('expenses.edit', $expense->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                <a href="{{ route('expenses.show', $expense->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Show
                                                </a>

                                                <x-delete-confirm :action="route('expenses.destroy', $expense->id)" 
                                                    :message="json_encode('Are you sure you want to delete expense ' . $expense->expense_title . '? This action cannot be undone.')" />
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No expenses found</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            @if (request()->hasAny(['search', 'status']))
                                                Try adjusting your search or filter to find what you're looking for.
                                            @else
                                                Get started by creating a new expense.
                                            @endif
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('expenses.create') }}"
                                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                Add Expense
                                            </a>
                                            @if (request()->hasAny(['search', 'status']))
                                                <a href="{{ route('expenses.index') }}"
                                                    class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                                    Clear Filters
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer with Pagination -->
            @if ($expenses->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-400 mb-4 sm:mb-0">
                            Showing {{ $expenses->firstItem() ?? 0 }} to {{ $expenses->lastItem() ?? 0 }} of {{ $expenses->total() }} results
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $expenses->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('Backend/js/chart.js') }}"></script>
<script>
    // Monthly Expenses Chart
    const monthlyCtx = document.getElementById('monthlyExpensesChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels ?? []),
            datasets: [{
                label: 'Expenses (Ks)',
                data: @json($chartData ?? []),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Ks ' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Ks ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush