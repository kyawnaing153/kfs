@php
    use App\Helpers\AppHelper;
    $helper = AppHelper::instance();
@endphp

@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Sales Management" />

    <div class="grid grid-cols-1 gap-6">
        <!-- Header with Stats -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Sales Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage all sales transactions and invoices
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('sales.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Sale
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Sales</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $totalSales }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-lg font-semibold text-yellow-600 dark:text-yellow-400">
                        {{ $pendingSales }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                        {{ $completedSales }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Sale This Year</p>
                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                        Ks {{ number_format($totalRevenue, 1) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Search and Filters -->
            <div class="p-6 pb-0">
                <form method="GET" action="{{ route('sales.index') }}" id="searchForm">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" id="searchInput"
                                placeholder="Search by code, customer..."
                                class="w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm
                                          focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                          dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                            @if (request('search'))
                                <a href="{{ route('sales.index', ['status' => request('status'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
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
                            <select name="status" onchange="document.getElementById('searchForm').submit()"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                           focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                           dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                          focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                          dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900"
                                placeholder="Start Date">
                        </div>

                        <div>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                          focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                          dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900"
                                placeholder="End Date">
                        </div>

                        <!-- Hidden buttons for form submission -->
                        <div class="hidden">
                            <button type="submit" id="submitBtn">Filter</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sales Table -->
            <div class="p-6 pt-4">
                @if ($sales->isEmpty())
                    <!-- Empty State -->
                    <div class="py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No sales found</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            @if (request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                                Try adjusting your search or filter
                            @else
                                Get started by creating a new sales transaction.
                            @endif
                        </p>
                        <div class="mt-6">
                            @if (request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                                <a href="{{ route('sales.index') }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                                    Clear Filters
                                </a>
                            @else
                                <a href="{{ route('sales.create') }}"
                                    class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create New Sale
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Sales Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <th
                                        class="px-4 py-3 min-w-[180px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sale Details
                                    </th>
                                    <th
                                        class="px-4 py-3 min-w-[180px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th
                                        class="px-4 py-3 min-w-[140px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount & Status
                                    </th>
                                    <th
                                        class="px-4 py-3 min-w-[140px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Payment
                                    </th>
                                    <th
                                        class="px-4 py-3 min-w-[100px] text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($sales as $sale)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <!-- Sale Details -->
                                        <td class="px-4 py-4">
                                            <div class="flex flex-col">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $sale->sale_code }}
                                                </div>
                                                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $helper->formatDate($sale->sale_date, 'd M, Y') }}
                                                </div>
                                                <div class="mt-1 text-xs text-gray-400">
                                                    Items: {{ $sale->items->count() }}
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Customer -->
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $sale->customer->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <a href="tel:{{ $sale->customer->phone_number }}"
                                                    class="hover:underline">
                                                    {{ $sale->customer->phone_number ?? ' No phone' }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $sale->customer->email ?? 'No email' }}
                                            </div>
                                        </td>

                                        <!-- Amount & Status -->
                                        <td class="px-4 py-4">
                                            <div class="space-y-2">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        Ks {{ number_format($sale->total, 1) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        Sub: Ks {{ number_format($sale->sub_total, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span
                                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                        @if ($sale->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                        @else
                                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                        {{ ucfirst($sale->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Payment Information -->
                                        <td class="px-4 py-4">
                                            <div class="space-y-1">
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">Paid:</span>
                                                    <span class="text-gray-900 dark:text-white"> Ks
                                                        {{ number_format($sale->total_paid, 1) }}</span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">Due:</span>
                                                    <span
                                                        class="{{ $sale->total_due > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                                        Ks {{ number_format($sale->total_due, 1) }}
                                                    </span>
                                                </div>
                                                @if ($sale->payment_type)
                                                    <div class="text-xs text-gray-500">
                                                        {{ ucfirst(str_replace('_', ' ', $sale->payment_type)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-4 py-4 text-center">
                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                <button @click="open = !open" @click.away="open = false"
                                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200
                                                            dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </button>

                                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95"
                                                    class="absolute right-0 z-20 mt-2 w-38 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5
                                                        dark:bg-gray-800"
                                                    style="display: none;">
                                                    <div class="py-1">
                                                        <a href="{{ route('sales.print', $sale->id) }}" target="_blank"
                                                            class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            Print
                                                        </a>

                                                        <form action="{{ route('sales.send-mail', $sale->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button type="submit"
                                                                class="flex w-full items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                Send Mail
                                                            </button>
                                                        </form>

                                                        <a href="{{ route('sales.show', $sale->id) }}"
                                                            class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            View
                                                        </a>

                                                        <a href="{{ route('sales.edit', $sale->id) }}"
                                                            class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            Edit
                                                        </a>
                                                        @if ($sale->status === 'pending')
                                                            <form method="POST"
                                                                action="{{ route('sales.complete', $sale->id) }}"
                                                                onsubmit="return confirm('Mark this sale as completed?')">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-green-600 hover:bg-green-50
                                                                            dark:text-green-400 dark:hover:bg-green-900/30">
                                                                    <svg class="h-4 w-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Mark as Completed
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if ($sale->status === 'pending')
                                                            <!-- Delete button using component -->
                                                            <x-delete-confirm :action="route('sales.destroy', $sale->id)" :message="json_encode(
                                                                'Are you sure you want to delete this sale ' .
                                                                    $sale->sale_code .
                                                                    '? This action cannot be undone.',
                                                            )" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-6 flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing {{ $sales->firstItem() ?? 0 }} to {{ $sales->lastItem() ?? 0 }} of
                            {{ $sales->total() }} results
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $sales->onEachSide(1)->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            const dateInputs = document.querySelectorAll('input[type="date"]');

            // Auto-submit search with debounce
            let searchTimeout;

            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 3000);
                });
            }

            // Auto-submit date filters on change
            dateInputs.forEach(input => {
                input.addEventListener('change', function() {
                    searchForm.submit();
                });
            });

            // Initialize date pickers (if using flatpickr)
            if (typeof flatpickr !== 'undefined') {
                flatpickr('input[type="date"]', {
                    dateFormat: 'Y-m-d',
                    allowInput: false
                });
            }
        });
    </script>
@endpush
