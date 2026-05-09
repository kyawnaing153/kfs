@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Quotation Details: ' . $quotation->quotation_code" />

    <div class="space-y-6">
        <!-- Quotation Summary Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                <!-- Quotation Info -->
                <div class=" md:d-block md:space-x-0 md:space-y-4">
                    <div class="flex flex-col md:flex-row md:items-start md:gap-6">
                        <!-- Quotation Code & Status -->
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $quotation->quotation_code }}
                            </h1>

                            <div class="flex flex-wrap gap-2">
                                <!-- Status Badge -->
                                @php
                                    $statusColors = [
                                        'submitted' =>
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'approved' =>
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        'expired' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                                        'converted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$quotation->status] ?? $statusColors['submitted'] }}">
                                    {{ ucfirst($quotation->status) }}
                                </span>

                                <!-- Type Badge -->
                                <span
                                    class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-sm font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                    {{ ucfirst($quotation->type) }}
                                </span>

                                <!-- Transport Required Badge -->
                                @if ($quotation->transport_required)
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Transport Required
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">
                                        {{ $quotation->customer->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $quotation->customer->email }}
                                    </p>
                                    @if ($quotation->customer->phone_number)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $quotation->customer->phone_number }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quotation Dates -->
                    <div class="mt-4 md:mt-0">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Quotation Date</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d M Y') }}
                            </p>
                        </div>
                        @if ($quotation->rent_date)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rent Date</p>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($quotation->rent_date)->format('d M Y') }}
                                    @if ($quotation->rent_duration)
                                        <span class="text-xs text-gray-500">
                                            ({{ $quotation->rent_duration }} days)
                                        </span>
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 md:mt-0 flex items-center gap-3 md:flex-row md:items-start md:justify-between">
                    <div>
                        @if ($quotation->status === 'submitted')
                            <a href="{{ route('customer-quotation.edit', $quotation->id) }}"
                                class="inline-flex items-center gap-2 rounded-lg border border-brand-300 bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 dark:border-brand-700 dark:bg-brand-600 dark:hover:bg-brand-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        @endif

                        <a href="{{ route('customer-quotation.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white mt-2 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>

                    <div>
                        @if ($quotation->status === 'submitted')
                            <form method="POST" action="#" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Approve this quotation?')"
                                    class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:border-green-800 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="#" class="inline-block mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Reject this quotation?')"
                                    class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reject
                                </button>
                            </form>
                        @endif

                        @if ($quotation->status === 'approved')
                            <a href="{{ route('customer-quotation.convert-to-rent', $quotation->id) }}" onclick="return confirm('Convert this quotation to a rent?')"
                                class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 mt-2 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Convert to Rent
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column: Quotation Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Financial Summary Card -->
                <x-common.component-card title="Financial Summary">
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <!-- Sub Total -->
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sub Total</p>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                Ks {{ number_format($quotation->sub_total, 0) }}
                            </p>
                        </div>

                        <!-- Deposit -->
                        <div
                            class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Deposit</p>
                            <p class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                                +Ks {{ number_format($quotation->deposit, 0) }}
                            </p>
                        </div>

                        <!-- Transport -->
                        @if ($quotation->transport_required)
                            <div
                                class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transport</p>
                                <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                    +Ks {{ number_format($quotation->transport, 0) }}
                                </p>
                            </div>
                        @endif

                        <!-- Total -->
                        <div
                            class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                            <p class="text-xl font-semibold text-green-600 dark:text-green-400">
                                Ks {{ number_format($quotation->total, 0) }}
                            </p>
                        </div>
                    </div>

                    <!-- Discount Row -->
                    @if ($quotation->discount > 0)
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Discount</p>
                                <p class="text-xl font-semibold text-red-600 dark:text-red-400">
                                    -Ks {{ number_format($quotation->discount, 0) }}
                                </p>
                            </div>
                        </div>
                    @endif
                </x-common.component-card>

                <!-- Quotation Items Card -->
                <x-common.component-card title="Quotation Items">
                    @if ($quotation->items->isEmpty())
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No items in this quotation
                            </h3>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Product
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Quantity
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Unit Price
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($quotation->items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <!-- Product -->
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">

                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            {{ $item->productVariant->product->product_name ?? 'Product not found' }}
                                                        </div>
                                                        @if ($item->productVariant && $item->productVariant->size)
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                Size: {{ $item->productVariant->size }}
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Quantity -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $item->qty }} {{ $item->unit ?? 'unit' }}
                                                </div>
                                            </td>

                                            <!-- Unit Price -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    Ks {{ number_format($item->unit_price, 0) }}
                                                </div>
                                            </td>

                                            <!-- Total -->
                                            <td class="px-4 py-4 text-right">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Ks {{ number_format($item->total, 0) }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <td colspan="3"
                                            class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Sub Total:
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                Ks {{ number_format($quotation->items->sum('total'), 0) }}
                                            </div>
                                        </td>
                                    </tr>
                                    @if ($quotation->discount > 0)
                                        <tr class="bg-gray-50 dark:bg-gray-800/50">
                                            <td colspan="3"
                                                class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Discount:
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <div class="text-sm font-semibold text-red-600 dark:text-red-400">
                                                    -Ks {{ number_format($quotation->discount, 0) }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="bg-gray-100 dark:bg-gray-800/70">
                                        <td colspan="3"
                                            class="px-4 py-3 text-right text-base font-bold text-gray-900 dark:text-white">
                                            Total:
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <div class="text-base font-bold text-green-600 dark:text-green-400">
                                                Ks {{ number_format($quotation->total, 0) }}
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </x-common.component-card>

                <!-- Transport Address Card -->
                @if ($quotation->transport_required && $quotation->transport_address)
                    <x-common.component-card title="Transport Information">
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Delivery Address</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $quotation->transport_address }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                        Transport Fee: Ks {{ number_format($quotation->transport, 0) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-common.component-card>
                @endif
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Quotation Stats Card -->
                <x-common.component-card title="Quotation Stats">
                    <div class="space-y-4">
                        <!-- Items Summary -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Items Summary</p>
                            <div class="mt-1 grid grid-cols-2 gap-2">
                                <div class="rounded-lg bg-gray-50 p-2 dark:bg-gray-800">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Items</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $quotation->items->sum('qty') }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-gray-50 p-2 dark:bg-gray-800">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Unique Products</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $quotation->items->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Date Information -->
                        <div class="border-t border-gray-200 pt-4 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Date Information</p>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Created:</span>
                                    <span class="text-gray-900 dark:text-white">
                                        {{ $quotation->created_at->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Last Updated:</span>
                                    <span class="text-gray-900 dark:text-white">
                                        {{ $quotation->updated_at->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                                @if ($quotation->quotation_date)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500 dark:text-gray-400">Quotation Date:</span>
                                        <span class="text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Rent Information -->
                        @if ($quotation->rent_date || $quotation->rent_duration)
                            <div class="border-t border-gray-200 pt-4 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Rent Information</p>
                                <div class="space-y-2">
                                    @if ($quotation->rent_date)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Rent Date:</span>
                                            <span class="text-gray-900 dark:text-white">
                                                {{ \Carbon\Carbon::parse($quotation->rent_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($quotation->rent_duration)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Duration:</span>
                                            <span class="text-gray-900 dark:text-white">
                                                {{ $quotation->rent_duration }} days
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </x-common.component-card>

                <!-- Notes Card -->
                @if ($quotation->notes)
                    <x-common.component-card title="Notes">
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400">
                                {{ nl2br(e($quotation->notes)) }}
                            </div>
                        </div>
                    </x-common.component-card>
                @endif

                <!-- Quick Actions Card -->
                <x-common.component-card title="Quick Actions">
                    <div class="space-y-2">
                        <!-- Send Email Button -->
                        {{-- <button type="button" onclick="sendQuotationEmail({{ $quotation->id }})"
                            class="flex w-full items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Send Email
                        </button> --}}

                        <!-- Back Button -->
                        <a href="{{ route('customer-quotation.index') }}"
                            class="flex w-full items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>
                </x-common.component-card>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function sendQuotationEmail(quotationId) {
            if (confirm('Send this quotation to the customer via email?')) {
                fetch(`{{ route('customer-quotation.index', '') }}/${quotationId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Quotation sent successfully to {{ $quotation->customer->email }}!');
                            if (data.sent_to) {
                                console.log('Email sent to:', data.sent_to);
                            }
                        } else {
                            alert('Failed to send quotation. Please try again.\\nError: ' + (data.message ||
                                'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            }
        }

        // Add print styling for better print layout
        window.onbeforeprint = function() {
            document.body.classList.add('print-mode');
        };

        window.onafterprint = function() {
            document.body.classList.remove('print-mode');
        };
    </script>

    <style>
        @media print {

            .action-buttons,
            .quick-actions,
            .no-print {
                display: none !important;
            }

            body.print-mode .rounded-lg,
            body.print-mode .rounded-2xl {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }

            body.print-mode {
                background: white !important;
            }

            body.print-mode .dark\:bg-gray-900,
            body.print-mode .dark\:bg-gray-800 {
                background: white !important;
            }

            body.print-mode .text-gray-900,
            body.print-mode .dark\:text-white {
                color: black !important;
            }
        }
    </style>
@endpush
