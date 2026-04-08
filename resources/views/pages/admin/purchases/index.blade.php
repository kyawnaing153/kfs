@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-semibold">Purchase Management</h1>
            <a href="{{ route('purchases.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + New Purchase
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-gray-500 text-sm">Total Purchases</h3>
                <p class="text-2xl font-bold">Ks {{ number_format($statistics['total_purchases'], 1) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-gray-500 text-sm">Pending Delivery</h3>
                <p class="text-2xl font-bold">{{ $statistics['pending_delivery'] }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="text-gray-500 text-sm">This Month Purchases</h3>
                <p class="text-2xl font-bold text-green-600">Ks {{ number_format($statistics['this_month_purchases'], 1) }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-6 p-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" placeholder="Search by PO or Supplier" value="{{ request('search') }}"
                    class="border rounded-lg px-3 py-2">

                <select name="status" class="border rounded-lg px-3 py-2">
                    <option value="all">All Status</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Delivered</option>
                </select>

                <select name="payment_status" class="border rounded-lg px-3 py-2">
                    <option value="all">All Payment</option>
                    <option value="0" {{ request('payment_status') == '0' ? 'selected' : '' }}>Unpaid</option>
                    <option value="1" {{ request('payment_status') == '1' ? 'selected' : '' }}>Paid</option>
                </select>

                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Filter</button>
            </form>
        </div>

        <!-- Purchases Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PO Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($purchases as $purchase)
                        <tr>
                            <td class="px-6 py-4">{{ $purchase->purchase_code }}
                                <p><span class="text-sm text-gray-500 dark:text-gray-400">{{ $purchase->purchase_date }}</span></p>
                            </td>
                            <td class="px-6 py-4">{{ $purchase->supplier->name }} 
                                <p><span class="text-sm text-gray-500 dark:text-gray-400 hover:underline"> <a href="tel:+{{$purchase->supplier->phone_number}}">{{ $purchase->supplier->phone_number }}</a></span></p>
                            </td>
                            <td class="px-6 py-4">${{ number_format($purchase->total_amount, 0) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $purchase->status_badge_class }}">
                                    {{ $purchase->status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $purchase->payment_badge_class }}">
                                    {{ $purchase->payment_status_text }}
                                </span>
                            </td>
                            <!-- Action Column - Dropdown style -->
                            <td class="px-4 py-4 text-right">
                                <div class="relative inline-block text-left" x-data="{ open{{ $purchase->id }}: false }">
                                    <button type="button" @click="open{{ $purchase->id }} = !open{{ $purchase->id }}"
                                        @click.away="open{{ $purchase->id }} = false"
                                        class="btn btn-secondary dropdown-toggle action-dropdown-toggle flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                        aria-haspopup="true" :aria-expanded="open{{ $purchase->id }}">
                                        <!-- Heroicon: Ellipsis Vertical -->
                                        <svg xmlns="http://www.w3.org" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>

                                    <div x-show="open{{ $purchase->id }}"
                                        x-transition:enter="transition ease-out duration-100"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-blue-500/10 dark:bg-gray-800"
                                        role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                        style="display: none;">
                                        <div class="py-1" role="none">
                                            @if ($purchase->status == 0)
                                                <!-- Delivery Status -->
                                                <form method="POST"
                                                    action="{{ route('purchases.mark-as-delivered', $purchase->id) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-green-800 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        role="menuitem">
                                                        <i class="fas fa-window-close text-red-500"></i>
                                                        {{ $purchase->status == 1 ? 'Mark as Delivered' : 'Mark as Not Delivered' }}
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($purchase->payment_status == 0)
                                                <!-- update-payment -->
                                                <form method="POST"
                                                    action="{{ route('purchases.update-payment', $purchase->id) }}"
                                                    class="inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-yellow-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                        role="menuitem">
                                                        <i class="fas fa-window-close text-red-500"></i>
                                                        {{ $purchase->payment_status == 1 ? 'Mark as Unpaid' : 'Mark as Paid' }}
                                                    </button>
                                                </form>
                                            @endif
                                            <!-- Edit -->
                                            <a href="{{ route('purchases.edit', $purchase->id) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                role="menuitem">
                                                <i class="fas fa-edit text-blue-500"></i>
                                                Edit
                                            </a>

                                            <!-- Show -->
                                            <a href="{{ route('purchases.show', $purchase->id) }}"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
                                                role="menuitem">
                                                <i class="fas fa-eye text-green-500"></i>
                                                Show
                                            </a>

                                            <!-- Delete button using component -->
                                            <x-delete-confirm :action="route('purchases.destroy', $purchase->id)" :message="json_encode(
                                                'Are you sure you want to delete purchase ' .
                                                    $purchase->purchase_code .
                                                    '? This action cannot be undone.',
                                            )" />
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No purchases found
                                    </h3>
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        @if (request()->hasAny(['search', 'status']))
                                            Try adjusting your search or filter to find what you're looking for.
                                        @else
                                            Get started by creating a new purchase.
                                        @endif
                                    </p>
                                    <div class="mt-6">
                                        <a href="{{ route('purchases.create') }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                            Add Purchase
                                        </a>
                                        @if (request()->hasAny(['search', 'status']))
                                            <a href="{{ route('purchases.index') }}"
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
            <div class="px-6 py-4">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
@endsection
