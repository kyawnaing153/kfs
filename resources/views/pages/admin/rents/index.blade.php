@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Rent Management" />

    <div class="grid grid-cols-1 gap-6">
        <!-- Header with Stats -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Rental Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage all rental transactions and returns
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('rents.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create New Rent
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Rents</p>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $rents->count() }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">
                        {{ $rents->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ongoing</p>
                    <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">
                        {{ $rents->where('status', 'ongoing')->count() }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                    <p class="text-2xl font-semibold text-green-600 dark:text-green-400">
                        {{ $rents->where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Tabs Component -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Tabs Header -->
            <div class="border-b border-gray-200 dark:border-gray-800">
                <div class="flex">
                    <button data-tab="rents"
                        class="tab-btn flex-1 px-6 py-4 text-sm font-medium transition-colors
                               {{ $activeTab === 'rents' ? 'text-brand-600 border-b-2 border-brand-600 dark:text-brand-400 dark:border-brand-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Rental Transactions
                        <span class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-xs dark:bg-gray-800">
                            {{ $rents->count() }}
                        </span>
                    </button>
                    <button data-tab="returns"
                        class="tab-btn flex-1 px-6 py-4 text-sm font-medium transition-colors
                               {{ $activeTab === 'returns' ? 'text-brand-600 border-b-2 border-brand-600 dark:text-brand-400 dark:border-brand-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        Returns
                        <span class="ml-2 rounded-full bg-gray-100 px-2 py-0.5 text-xs dark:bg-gray-800">
                            {{ $returns->count() }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="p-6 pb-0">
                <form method="GET" action="{{ route('rents.index') }}" id="searchForm">
                    <input type="hidden" name="tab" id="activeTabInput" value="{{ $activeTab }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search Input -->
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ $search }}" id="searchInput"
                                placeholder="Search..."
                                class="w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm
                                          focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                          dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                            @if ($search)
                                <a href="{{ route('rents.index', ['tab' => $activeTab, 'status' => $status]) }}"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <!-- Status Filter (Only for Rents tab) -->
                        <div id="statusFilterContainer" class="{{ $activeTab === 'returns' ? 'hidden' : '' }}">
                            <select name="status" onchange="document.getElementById('searchForm').submit()"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                           focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                           dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="ongoing" {{ $status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                        </div>

                        <!-- Status Filter (Only for Returns tab) -->
                        <div id="ReturnstatusFilterContainer" class="{{ $activeTab === 'rents' ? 'hidden' : '' }}">
                            <select name="return_status" onchange="document.getElementById('searchForm').submit()"
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm
                                           focus:border-brand-500 focus:ring-2 focus:ring-brand-200 dark:border-gray-700
                                           dark:bg-gray-900 dark:focus:border-brand-500 dark:focus:ring-brand-900">
                                <option value="all" {{ $returnStatus === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="partial" {{ $returnStatus === 'partial' ? 'selected' : '' }}>Partial
                                </option>
                                <option value="completed" {{ $returnStatus === 'completed' ? 'selected' : '' }}>Completed
                                </option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Rents Tab Content -->
            <div id="rents-tab" class="tab-content {{ $activeTab === 'rents' ? 'block' : 'hidden' }}">
                <div class="p-6 pt-4">
                    @if ($rents->isEmpty())
                        <!-- Empty State for Rents -->
                        <div class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No rents found</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                @if ($search || $status !== 'all')
                                    Try adjusting your search or filter
                                @else
                                    Get started by creating a new rental transaction.
                                @endif
                            </p>
                            <div class="mt-6">
                                @if ($search || $status !== 'all')
                                    <a href="{{ route('rents.index', ['tab' => 'rents']) }}"
                                        class="inline-flex items-center gap-2 rounded-lg bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                                        Clear Filters
                                    </a>
                                @else
                                    <a href="{{ route('rents.create') }}"
                                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create New Rent
                                    </a>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Rents Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th
                                            class="px-4 py-3 min-w-[220px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rent Details
                                        </th>
                                        <th
                                            class="px-4 py-3 min-w-[200px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th
                                            class="px-4 py-3 min-w-[180px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount & Status
                                        </th>
                                        <th
                                            class="px-4 py-3 min-w-[100px] text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($rents as $rent)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <!-- Rent Details -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col">
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        {{ $rent->rent_code }}
                                                    </div>
                                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $rent->rent_date }}
                                                    </div>
                                                    <div class="mt-1 text-xs text-gray-400">
                                                        Items: {{ $rent->items->count() }}
                                                    </div>
                                                </div>
                                            </td>
                                            @php
                                                $phones = array_map(
                                                    'trim',
                                                    explode(',', $rent->customer->phone_number ?? ''),
                                                );
                                            @endphp

                                            <!-- Customer -->
                                            <td class="px-4 py-4">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $rent->customer->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $phones[0] ?? ($phones[1] ?? 'No phone') }}
                                                </div>
                                            </td>

                                            <!-- Amount & Status -->
                                            <td class="px-4 py-4">
                                                <div class="space-y-2">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            Ks {{ number_format($rent->total, 1) }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            Due: Ks {{ number_format($rent->total_due, 1) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span
                                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                        @if ($rent->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                        @elseif($rent->status === 'ongoing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                        @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                            {{ ucfirst($rent->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-4 py-4 text-center">
                                                <!-- Action dropdown (same as before) -->
                                                <div class="relative inline-block text-left" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.away="open = false"
                                                        class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200
                                                                dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </button>

                                                    <div x-show="open"
                                                        x-transition:enter="transition ease-out duration-100"
                                                        x-transition:enter-start="opacity-0 scale-95"
                                                        x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="opacity-100 scale-100"
                                                        x-transition:leave-end="opacity-0 scale-95"
                                                        class="absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5
                                                            dark:bg-gray-800"
                                                        style="display: none;">
                                                        <div class="py-1">
                                                            <a href="{{ route('rents.show', $rent->id) }}"
                                                                class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                View
                                                            </a>
                                                            <a href="{{ route('rents.print', $rent->id) }}"
                                                                class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                Invoice
                                                            </a>

                                                            <form action="{{ route('rents.send-mail', $rent->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                    Send Mail
                                                                </button>
                                                            </form>

                                                            @if ($rent->status === 'pending')
                                                                <a href="{{ route('rents.edit', $rent->id) }}"
                                                                    class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                    Edit
                                                                </a>
                                                            @endif
                                                            @if ($rent->status !== 'completed')
                                                                <a href="{{ route('rents.returns.create', $rent->id) }}"
                                                                    class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                    Return Items
                                                                </a>
                                                                <a href="{{ route('rents.payments.create', $rent->id) }}"
                                                                    class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                    Record Payment
                                                                </a>
                                                            @endif
                                                            @if ($rent->status === 'pending')
                                                                <!-- Delete button using component -->
                                                                <x-delete-confirm :action="route('rents.destroy', $rent->id)" :message="json_encode(
                                                                    'Are you sure you want to delete rent ' .
                                                                        $rent->rent_code .
                                                                        '? This action cannot be undone.',
                                                                )"
                                                                    buttonText="Cancel Rent" />
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
                    @endif
                </div>
            </div>

            <!-- Returns Tab Content -->
            <div id="returns-tab" class="tab-content {{ $activeTab === 'returns' ? 'block' : 'hidden' }}">
                <div class="p-6 pt-4">
                    @if ($returns->isEmpty())
                        <!-- Empty State for Returns -->
                        <div class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No returns found</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                @if ($search)
                                    Try adjusting your search
                                @else
                                    No returns have been recorded yet.
                                @endif
                            </p>
                            @if ($search)
                                <div class="mt-6">
                                    <a href="{{ route('rents.index', ['tab' => 'returns']) }}"
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
                                        <th
                                            class="px-4 py-3 min-w-[200px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Return Details
                                        </th>
                                        <th
                                            class="px-4 py-3 min-w-[200px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rent & Customer
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Items Returned
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                                        {{ $return->rent->rent_code ?? 'N/A' }}
                                                    </div>
                                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $return->return_date }}
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        {{ $return->rent->sub_total }} ks
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Rent & Customer -->
                                            <td class="px-4 py-4">
                                                <div class="flex flex-col">
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        {{ $return->rent->rent_date }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $return->rent->customer->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        <a href="tel:{{ $phones[0] }}"
                                                            class="hover:underline">
                                                            {{ $phones[0] ?? $phones[1] ?? 'No phone' }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Items Returned -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm">
                                                    {{ $return->items->count() }} items
                                                </div>
                                                @if ($return->items->count() > 0)
                                                    <div class="mt-1 text-xs text-gray-500">
                                                        {{ $return->items->pluck('rentItem.productVariant.product.product_name')->implode(', ') }}
                                                    </div>
                                                @endif
                                            </td>

                                            <!-- Status -->
                                            <td class="px-4 py-4">
                                                @php
                                                    $statusColors = [
                                                        'pending' =>
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                                        'partial' =>
                                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                                        'completed' =>
                                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                                        'overdue' =>
                                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                                    ];
                                                    $status = $return->status ?? 'pending';
                                                @endphp
                                                <span
                                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$status] ?? $statusColors['pending'] }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>

                                            <!-- Actions -->
                                            <td class="px-4 py-4">
                                                <a href="{{ route('rents.returns.show', [$return->rent_id, $return->id]) }}"
                                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    View
                                                </a>
                                                <a href="{{ route('rents.returns.print', [$return->rent_id, $return->id, 'autoprint' => true]) }}"
                                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 14h6m-6-4h6m-7 8h8a2 2 0 002-2V6a2 2 0 00-2-2H8l-2 2H6a2 2 0 00-2 2v10a2 2 0 002 2h2" />
                                                    </svg>

                                                    Invoice
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');
            const activeTabInput = document.getElementById('activeTabInput');
            const statusFilterContainer = document.getElementById('statusFilterContainer');
            const returnStatusFilterContainer = document.getElementById('ReturnstatusFilterContainer');
            const searchForm = document.getElementById('searchForm');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabId = this.dataset.tab;

                    // Update active tab button
                    tabButtons.forEach(btn => {
                        btn.classList.remove('text-brand-600', 'border-b-2',
                            'border-brand-600',
                            'dark:text-brand-400', 'dark:border-brand-400');
                        btn.classList.add('text-gray-500', 'dark:text-gray-400');
                    });

                    this.classList.remove('text-gray-500', 'dark:text-gray-400');
                    this.classList.add('text-brand-600', 'border-b-2', 'border-brand-600',
                        'dark:text-brand-400', 'dark:border-brand-400');

                    // Show/hide tab content
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });

                    document.getElementById(`${tabId}-tab`).classList.remove('hidden');

                    // Update the hidden tab input
                    if (activeTabInput) {
                        activeTabInput.value = tabId;
                    }

                    // Show/hide status filter based on active tab
                    if (statusFilterContainer) {
                        if (tabId === 'rents') {
                            statusFilterContainer.classList.remove('hidden');
                            returnStatusFilterContainer.classList.add('hidden');
                        } else {
                            statusFilterContainer.classList.add('hidden');
                            returnStatusFilterContainer.classList.remove('hidden');
                        }
                    }

                    // Submit form to update URL with tab parameter
                    //searchForm.submit();
                });
            });

            // Auto-submit search with debounce
            let searchTimeout;
            const searchInput = document.getElementById('searchInput');

            if (searchInput && searchForm) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        searchForm.submit();
                    }, 5000);
                });
            }
        });
    </script>
@endpush
