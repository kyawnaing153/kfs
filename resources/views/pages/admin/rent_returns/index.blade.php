@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Returns Management" />

    <div class="grid grid-cols-1 gap-6">
        <!-- Header -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Returns Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage all rental returns
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('rents.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-brand-600 bg-white px-4 py-2.5 text-sm font-medium text-brand-600 hover:bg-brand-50 dark:border-brand-400 dark:bg-gray-800 dark:text-brand-400 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        View Rents
                    </a>
                </div>
            </div>
        </div>

        <!-- Returns Content -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Search Bar -->
            <div class="p-6">
                <form method="GET" action="{{ route('rent-payments.index') }}" id="searchForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ $search }}" id="searchInput"
                                placeholder="Search by rent code, customer name, phone..."
                                class="w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm
                                          focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                          dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                            @if ($search)
                                <a href="{{ route('rent_returns.index') }}"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select name="status" id="statusFilter" onchange="document.getElementById('searchForm').submit()"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                           focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                           dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>

                        <!-- Sort Options -->
                        <div class="md:col-span-2 flex gap-4">
                            <select name="order_by" onchange="document.getElementById('searchForm').submit()"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                           focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                           dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                                <option value="return_date" {{ $orderBy === 'return_date' ? 'selected' : '' }}>Sort by Date</option>
                                <option value="created_at" {{ $orderBy === 'created_at' ? 'selected' : '' }}>Sort by Created Date</option>
                                <option value="id" {{ $orderBy === 'id' ? 'selected' : '' }}>Sort by Return ID</option>
                            </select>
                            <select name="order_dir" onchange="document.getElementById('searchForm').submit()"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                           focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                           dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                                <option value="desc" {{ $orderDir === 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ $orderDir === 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Returns Table -->
            <div class="p-6 pt-0">
                @if ($returns->isEmpty())
                    <!-- Empty State -->
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No returns found</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            @if ($search)
                                Try adjusting your search criteria
                            @else
                                No returns have been recorded yet. Returns will appear here when processed.
                            @endif
                        </p>
                        @if ($search)
                            <div class="mt-6">
                                <a href="{{ route('rent_returns.index') }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                                    Clear Search
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Returns Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[180px]">
                                        Return Details
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">
                                        Rent Information
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Financial Summary
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($returns as $return)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <!-- Return Details -->
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    Return #{{ $return->id }}
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }}
                                                </div>
                                                <div class="mt-1 text-xs text-gray-400">
                                                    {{ $return->items->count() }} items
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Rent Information -->
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $return->rent->rent_code ?? 'N/A' }}
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($return->rent->rent_date ?? now())->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $return->total_days }} days
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Customer -->
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $return->rent->customer->name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $return->rent->customer->phone_number ?? 'No phone' }}
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Financial Summary -->
                                        <td class="px-4 py-4">
                                            <div class="space-y-1">
                                                @if($return->refund_amount > 0)
                                                <div class="text-sm">
                                                    <span class="text-gray-500">Refund:</span>
                                                    <span class="font-medium text-blue-600 dark:text-blue-400 ml-1">
                                                        Ks {{ number_format($return->refund_amount, 1) }}
                                                    </span>
                                                </div>
                                                @endif
                                                @if($return->collect_amount > 0)
                                                <div class="text-sm">
                                                    <span class="text-gray-500">Collect:</span>
                                                    <span class="font-medium text-green-600 dark:text-green-400 ml-1">
                                                        Ks {{ number_format($return->collect_amount, 1) }}
                                                    </span>
                                                </div>
                                                @endif
                                                @if($return->items->sum('damage_fee') > 0)
                                                <div class="text-xs text-red-600 dark:text-red-400">
                                                    Damage: Ks {{ number_format($return->items->sum('damage_fee'), 1) }}
                                                </div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="px-4 py-4">
                                            @php
                                                $statusColors = [
                                                    'partial' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                ];
                                                $returnStatus = $return->status ?? 'partial';
                                            @endphp
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$returnStatus] ?? $statusColors['partial'] }}">
                                                {{ ucfirst($returnStatus) }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ route('rents.returns.show', [$return->rent_id, $return->id]) }}"
                                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View Details
                                                </a>
                                                <a href="{{ route('rents.returns.print', [$return->rent_id, $return->id, 'autoprint' => true]) }}"
                                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                                    target="_blank">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 14h6m-6-4h6m-7 8h8a2 2 0 002-2V6a2 2 0 00-2-2H8l-2 2H6a2 2 0 00-2 2v10a2 2 0 002 2h2" />
                                                    </svg>
                                                    Print Receipt
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination (if applicable) -->
                    @if(method_exists($returns, 'links'))
                        <div class="mt-6">
                            {{ $returns->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit search with debounce
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');

            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 500);
                });
            }

            // Auto-submit on Enter key
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchForm.submit();
                    }
                });
            }
        });
    </script>
@endpush