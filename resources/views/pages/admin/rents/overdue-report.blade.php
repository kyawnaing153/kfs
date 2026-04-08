@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Overdue Rents Report" />

    <div class="grid grid-cols-1 gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Overdue Rental Summary</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Records where rent date is older than 30 days and payment condition is overdue.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('rents.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Back
                    </a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Overdue</p>
                    <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ $summary['total_overdue_rents'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Rent Date Overdue</p>
                    <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $summary['rent_date_overdue_count'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Payment Overdue</p>
                    <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400">{{ $summary['payment_overdue_count'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No Payment Ever</p>
                    <p class="text-2xl font-semibold text-gray-700 dark:text-gray-300">{{ $summary['no_payment_ever_count'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Overdue Rent Details</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    As of {{ $summary['as_of_date'] ?? now()->toDateString() }} | Threshold date: {{ $summary['threshold_date'] ?? now()->subDays(30)->toDateString() }}
                </p>
            </div>

            <div class="p-6 pt-4">
                @if ($rents->isEmpty())
                    <div class="py-12 text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">No overdue rents found</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">All rent records are currently within expected date ranges.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <th class="px-4 py-3 min-w-[220px] text-left text-xs font-medium uppercase tracking-wider text-gray-500">Rent Details</th>
                                    <th class="px-4 py-3 min-w-[200px] text-left text-xs font-medium uppercase tracking-wider text-gray-500">Customer</th>
                                    <th class="px-4 py-3 min-w-[240px] text-left text-xs font-medium uppercase tracking-wider text-gray-500">Overdue Snapshot</th>
                                    <th class="px-4 py-3 min-w-[170px] text-left text-xs font-medium uppercase tracking-wider text-gray-500">Balance</th>
                                    <th class="px-4 py-3 min-w-[130px] text-center text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($rents as $rent)
                                    @php
                                        $phones = array_map('trim', explode(',', $rent->customer->phone_number ?? ''));
                                        $contactPhone = $phones[0] ?? ($phones[1] ?? 'No phone');
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white"> <a href="{{ route('rents.show', $rent->id) }}" class="text-blue-500 hover:text-blue-700 underline"> {{ $rent->rent_code }}</a></div>
                                            <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                Rent Date: {{ \Carbon\Carbon::parse($rent->rent_date)->format('d M Y') }}
                                            </div>
                                            <div class="mt-1 text-xs text-gray-400">Items: {{ $rent->items->count() }}</div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $rent->customer->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400"> <a href="tel:+{{ $contactPhone }}" class="text-blue-500 hover:text-blue-700 underline"> {{ $contactPhone }} </a></div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="space-y-1 text-sm text-gray-700 dark:text-gray-300">
                                                <div>Days Since Rent: <span class="font-medium">{{ $rent->days_since_rent }} days</span></div>
                                                <div>
                                                    Last Payment:
                                                    @if ($rent->last_payment_date)
                                                        <span class="font-medium">{{ \Carbon\Carbon::parse($rent->last_payment_date)->format('d M Y') }}</span>
                                                        <span class="text-xs text-gray-500">({{ $rent->days_since_last_payment }} days ago)</span>
                                                    @else
                                                        <span class="font-medium text-red-600 dark:text-red-400">No Payment</span>
                                                    @endif
                                                </div>
                                            </div>

                                            @php
                                                $reasonClass = str_contains(strtolower($rent->overdue_reason), 'no payment ever')
                                                    ? 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200'
                                                    : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                            @endphp
                                            <span class="mt-2 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $reasonClass }}">
                                                {{ $rent->overdue_reason }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                Total: Ks {{ number_format($rent->total, 1) }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Paid: Ks {{ number_format($rent->total_paid, 1) }}
                                            </div>
                                            <div class="mt-1 text-sm font-semibold text-red-600 dark:text-red-400">
                                                Due: Ks {{ number_format($rent->remaining_balance, 1) }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('rents.show', $rent->id) }}"
                                                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    View
                                                </a>
                                                <a href="{{ route('rents.payments.create', $rent->id) }}"
                                                    class="inline-flex items-center rounded-lg bg-brand-600 px-3 py-2 text-xs font-medium text-white hover:bg-brand-700">
                                                    Pay
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Report generated at {{ now()->format('d M Y H:i:s') }}</p>
                    <p class="text-sm font-semibold text-red-600 dark:text-red-400">
                        Total Overdue Due Amount: Ks {{ number_format($rents->sum('remaining_balance'), 1) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
