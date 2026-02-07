@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Payments Management" />

    <div class="grid grid-cols-1 gap-6">
        <!-- Stats Cards -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                        Payment Overview
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        View and manage all rental payments
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Payments</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $statistics['total_payments'] }} counts</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                        Ks {{ number_format($statistics['total_amount'], 0) }}
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Today's Payments</p>
                    <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                        {{ $statistics['today_count'] }} (Ks {{ number_format($statistics['today_amount'], 0) }})
                    </p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">This Month</p>
                    <p class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                        {{ $statistics['this_month_count'] }} (Ks {{ number_format($statistics['this_month_amount'], 1) }})
                    </p>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <x-common.component-card title="Payment History">
            <!-- Filters -->
            <div class="mb-6">
                <form method="GET" action="{{ route('rent-payments.index') }}" class="space-y-4" id="payment-filter-form">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Search</label>
                            <input type="text" name="search" placeholder="Rent code, customer, note..."
                                value="{{ request('search') }}"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Payment
                                Method</label>
                            <select name="payment_method"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">All Methods</option>
                                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash
                                </option>
                                <option value="bank_transfer"
                                    {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer
                                </option>
                                <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card
                                </option>
                                <option value="mobile_payment"
                                    {{ request('payment_method') == 'mobile_payment' ? 'selected' : '' }}>Mobile Payment
                                </option>
                                <option value="cheque" {{ request('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque
                                </option>
                                <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>

                        <!-- Payment Type -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Payment
                                Type</label>
                            <select name="payment_for"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">All Types</option>
                                @foreach ($paymentTypes as $type)
                                    <option value="{{ $type }}"
                                        {{ request('payment_for') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Customer Filter -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Customer</label>
                            <select name="customer_id"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                <option value="">All Customers</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Single Date -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Specific
                                Date</label>
                            <input type="date" name="payment_date" value="{{ request('payment_date') }}"
                                class="payment_date w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">From Date</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="payment_date w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">To Date</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="payment_date w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-2">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of
                            {{ $payments->total() }} payments
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500">
                                Filters
                            </button>
                            <a href="{{ route('rent-payments.index') }}"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            @if ($payments->isEmpty())
                <div class="py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No payments found</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        {{ request()->except('page') ? 'Try adjusting your filters.' : 'Payments will appear here when recorded.' }}
                    </p>
                    @if (!request()->except('page'))
                        <div class="mt-6">
                            <a href="{{ route('rents.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-medium text-white hover:bg-brand-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Go to Rents
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50">
                                <th class="px-4 py-3 min-w-[220px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment Details
                                </th>
                                <th class="px-4 py-3 min-w-[220px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rent & Customer
                                </th>
                                <th class="px-4 py-3 min-w-[220px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount & Method
                                </th>
                                <th class="px-4 py-3 min-w-[160px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($payments as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                Payment #{{ $payment->id }}
                                            </div>
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                {{ date('M d, Y', strtotime($payment->payment_date)) }}
                                            </div>
                                            @if ($payment->payment_for)
                                                <div class="mt-1 text-xs text-gray-400">
                                                    {{ $payment->payment_for }}
                                                </div>
                                            @endif
                                            @if ($payment->note)
                                                <div class="mt-1 text-xs text-gray-500 truncate max-w-xs"
                                                    title="{{ $payment->note }}">
                                                    {{ Str::limit($payment->note, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('rents.show', $payment->rent_id) }}"
                                                class="hover:text-brand-600 dark:hover:text-brand-400">
                                                {{ $payment->rent->rent_code }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $payment->rent->customer->name }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            Status:
                                            <span
                                                class="font-medium {{ $payment->rent->status === 'completed' ? 'text-green-600' : ($payment->rent->status === 'ongoing' ? 'text-blue-600' : 'text-yellow-600') }}">
                                                {{ ucfirst($payment->rent->status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="space-y-1">
                                            <div class="text-xs font-bold text-green-600 dark:text-green-400 whitespace-nowrap">
                                                Ks {{ number_format($payment->amount, 1) }}
                                            </div>
                                            <div>
                                                <span
                                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $payment->payment_method_badge }}">
                                                    {{ str_replace('_', ' ', ucfirst($payment->payment_method)) }}
                                                </span>
                                            </div>
                                            @if ($payment->period_start && $payment->period_end)
                                                <div class="text-xs text-gray-500">
                                                    {{ date('M d', strtotime($payment->period_start)) }} -
                                                    {{ date('M d, Y', strtotime($payment->period_end)) }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('rents.payments.show', [$payment->rent_id, $payment->id]) }}"
                                                class="inline-flex items-center gap-1 rounded-lg bg-brand-50 px-3 py-1.5 text-sm font-medium text-brand-700 hover:bg-brand-100 dark:bg-brand-900/30 dark:text-brand-300 dark:hover:bg-brand-900/50"
                                                title="View Receipt">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Receipt
                                            </a>
                                            <a href="{{ route('rents.show', $payment->rent_id) }}"
                                                class="inline-flex items-center gap-1 rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                                title="View Rent">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Rent
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($payments->hasPages())
                    <div class="mt-6">
                        {{ $payments->appends(request()->except('page'))->links() }}
                    </div>
                @endif
            @endif
        </x-common.component-card>
    </div>

    <script>
        $(document).ready(function() {
            // Set today as max date for date inputs
            const today = new Date().toISOString().split('T')[0];
            $('input[type="date"]').attr('max', today);

            // Auto-clear specific date when date range is selected
            $('input[name="start_date"], input[name="end_date"]').on('change', function() {
                if ($(this).val()) {
                    $('input[name="payment_date"]').val('');
                }
            });

            // Auto-clear date range when specific date is selected
            $('input[name="payment_date"]').on('change', function() {
                if ($(this).val()) {
                    $('input[name="start_date"], input[name="end_date"]').val('');
                }
            });

            // Validate date range
            $('input[name="end_date"]').on('change', function() {
                const startDate = $('input[name="start_date"]').val();
                const endDate = $(this).val();

                if (startDate && endDate && endDate < startDate) {
                    alert('End date must be after start date.');
                    $(this).val('');
                }
            });

            // Quick filter buttons for common date ranges
            $('.quick-filter').on('click', function(e) {
                e.preventDefault();
                const days = $(this).data('days');
                const endDate = new Date().toISOString().split('T')[0];
                const startDate = new Date();
                startDate.setDate(startDate.getDate() - days);
                const formattedStartDate = startDate.toISOString().split('T')[0];

                $('input[name="start_date"]').val(formattedStartDate);
                $('input[name="end_date"]').val(endDate);
                $('input[name="payment_date"]').val('');

                $('#payment-filter-form').submit();
            });
        });
    </script>
@endsection

@push('scripts')
    <script>
        flatpickr(".payment_date", {
            dateFormat: "Y-m-d",
            defaultDate: "{{ date('Y-m-d') }}"
        });
    </script>
@endpush
