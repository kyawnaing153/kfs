{{-- resources/views/backend/customers/rents.blade.php --}}
@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Customer Rents" />

    <div class="space-y-6">
        {{-- Customer Info Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 h-14 w-14">
                        <img class="h-14 w-14 rounded-full object-cover" src="{{ $customer->customerprofile() }}"
                            alt="{{ $customer->name }}">
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
                            {{ $customer->name }}
                        </h2>
                        <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $customer->email }}
                            </span>
                            @if ($customer->phone_number)
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <a href="tel:+{{ $customer->phone_number }}" class="underline hover:text-blue-700">
                                        {{ $customer->phone_number }}
                                    </a>
                                </span>
                            @endif
                            @if ($customer->company_name)
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ $customer->company_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="ml-auto">
                    <a href="{{ route('customers.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Customers
                    </a>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Rents --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Rents</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-800 dark:text-white/90">{{ $rents->count() }}</p>
                    </div>
                    <div class="rounded-full bg-blue-50 p-3 dark:bg-blue-900/20">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Ongoing Rents --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ongoing Rents</p>
                        <p class="mt-1 text-2xl font-semibold text-yellow-600 dark:text-yellow-400">
                            {{ $rents->where('status', 'ongoing')->count() }}
                        </p>
                    </div>
                    <div class="rounded-full bg-yellow-50 p-3 dark:bg-yellow-900/20">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Completed Rents --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Settled Rents</p>
                        <p class="mt-1 text-2xl font-semibold text-green-600 dark:text-green-400">
                            {{ $rents->where('status', 'completed')->count() }}
                        </p>
                    </div>
                    <div class="rounded-full bg-green-50 p-3 dark:bg-green-900/20">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Due --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Due</p>
                        <p class="mt-1 text-2xl font-semibold text-red-600 dark:text-red-400">
                            {{ number_format($rents->sum('total_due')) }} Ks
                        </p>
                    </div>
                    <div class="rounded-full bg-red-50 p-3 dark:bg-red-900/20">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rents Table --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
            {{-- Header --}}
            <div class="flex flex-col gap-4 px-5 mb-4 sm:flex-row sm:items-center sm:px-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Rent History
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        All rental transactions for this customer
                    </p>
                </div>
                <div class="ml-auto">
                    <a href="{{ route('rents.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Rent
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto px-5">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rent Code
                            </th>

                            <th
                                class="px-4 py-3 min-w-[180px] text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount & Status
                            </th>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deposit Amount
                            </th>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse ($rents as $rent)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                {{-- Rent Code --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('rents.print', $rent->id) }}"
                                            class="text-blue-500 hover:text-blue-700">
                                            {{ $rent->rent_code }}
                                        </a>
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $rent->rent_date }}
                                    </div>
                                    <div class="mt-1 text-xs text-gray-400">
                                        Items: {{ $rent->items->count() }}
                                    </div>
                                </td>

                                {{-- Total Amount --}}
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                                @if ($rent->status === 'completed')
                                                    {{ 'Settled' }}
                                                @else
                                                    {{ ucfirst($rent->status) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Paid --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-green-600 dark:text-green-400">
                                        {{ number_format($rent->deposit) }} Ks
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-4">
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

                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="opacity-0 scale-95"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-95"
                                            class="absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5
                                                            dark:bg-gray-800"
                                            style="display: none;">
                                            <div class="py-1">
                                                @if ($rent->status === 'pending')
                                                    <form action="{{ route('rents.mark-as-delivered', $rent->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-brand-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            Mark as Delivered
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('rents.print', $rent->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Invoice
                                                </a>
                                                <a href="{{ route('rents.show', $rent->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    View
                                                </a>
                                                <form action="{{ route('rents.send-mail', $rent->id) }}" method="POST">
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
                                                @if ($rent->status === 'ongoing')
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
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-gray-500 dark:text-gray-400">
                                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No rents found
                                        </h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            This customer hasn't made any rental transactions yet.
                                        </p>
                                        <div class="mt-6">
                                            <a href="{{ route('rents.create') }}"
                                                class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Create First Rent
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer with Pagination --}}
            @if (method_exists($rents, 'hasPages') && $rents->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $rents->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
