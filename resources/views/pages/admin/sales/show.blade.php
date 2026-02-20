@php
    use \App\Helpers\AppHelper;
    $helper = AppHelper::instance();
@endphp

@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Sale Details: ' . $sale->sale_code" />

    <div class="space-y-6">
        <!-- Sale Summary Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                <!-- Sale Info -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-start md:gap-6">
                        <!-- Sale Code & Status -->
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $sale->sale_code }}
                            </h1>
                            
                            <div class="flex flex-wrap gap-2">
                                <!-- Status Badge -->
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$sale->status] ?? $statusColors['pending'] }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                                
                                <!-- Payment Type -->
                                @if($sale->payment_type)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                    {{ ucfirst(str_replace('_', ' ', $sale->payment_type)) }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Customer Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">
                                        {{ $sale->customer->name ?? 'N/A' }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <a href="tel:+{{ $sale->customer->phone_number ?? '' }}">
                                            {{ $sale->customer->phone_number ?? 'No phone' }}
                                        </a>
                                    </p>
                                    @if($sale->customer->email)
                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $sale->customer->email }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-4 flex flex-wrap gap-2 md:mt-0 md:flex-col md:items-end">
                    
                    <a href="#" target="_blank"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        Print
                    </a>
                    
                    <a href="{{ route('sales.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </a>

                    @if(!$sale->isPending())
                        <a href="{{ route('sales.edit', $sale->id) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-brand-300 bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 dark:border-brand-700 dark:bg-brand-600 dark:hover:bg-brand-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        
                        <form action="{{ route('sales.complete', $sale->id) }}" method="POST" 
                              onsubmit="return confirm('Mark this sale as completed?')">
                            @csrf
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:border-green-800 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Mark as Completed
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column: Sale Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Financial Summary Card -->
                <x-common.component-card title="Financial Summary">
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <!-- Sub Total -->
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Sub Total</p>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                Ks {{ number_format($sale->sub_total, 1) }}
                            </p>
                        </div>
                        
                        <!-- Transport -->
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Transport</p>
                            <p class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                                + Ks {{ number_format($sale->transport, 1) }}
                            </p>
                        </div>

                        <!-- Discount -->
                        <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Discount</p>
                            <p class="text-xl font-semibold text-red-600 dark:text-red-400">
                                - Ks {{ number_format($sale->discount, 1) }}
                            </p>
                        </div>

                        <!-- Grand Total -->
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Grand Total</p>
                            <p class="text-xl font-semibold text-green-600 dark:text-green-400">
                                Ks {{ number_format($sale->total, 1) }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Payment Summary -->
                    <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-3">
                        <!-- Total Paid -->
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                            <p class="text-xl font-semibold text-green-600 dark:text-green-400">
                                Ks {{ number_format($sale->total_paid, 1) }}
                            </p>
                        </div>
                        
                        <!-- Total Due -->
                        <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Due</p>
                            <p class="text-xl font-semibold text-red-600 dark:text-red-400">
                                Ks {{ number_format($sale->total_due, 1) }}
                            </p>
                        </div>

                        <!-- Payment Progress -->
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900 col-span-2 md:col-span-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Payment Progress</p>
                            @php
                                $paymentPercentage = $sale->total > 0 ? round(($sale->total_paid / $sale->total) * 100, 0) : 0;
                            @endphp
                            <div class="mt-2">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium {{ $paymentPercentage >= 100 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                        {{ $paymentPercentage }}%
                                    </span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div class="h-full rounded-full {{ $paymentPercentage >= 100 ? 'bg-green-500' : 'bg-yellow-500' }}" 
                                         style="width: {{ min($paymentPercentage, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-common.component-card>

                <!-- Sale Items Card -->
                <x-common.component-card title="Sale Items">
                    @if($sale->items->isEmpty())
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No items in this sale</h3>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-4 py-3 min-w-[220px] text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($sale->items as $item)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <!-- Product -->
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if($item->productVariant && $item->productVariant->product && $item->productVariant->product->thumb_url)
                                                        <img src="{{ $item->productVariant->product->thumb_url }}" 
                                                             alt="{{ $item->productVariant->product->product_name }}"
                                                             class="h-10 w-10 rounded-lg object-cover">
                                                    @endif
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            {{ $item->productVariant->product->product_name ?? 'N/A' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $item->productVariant->name ?? 'Standard' }}
                                                            @if($item->unit)
                                                                <span class="text-xs">({{ $item->unit }})</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            SKU: {{ $item->productVariant->sku ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Quantity -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $item->sale_qty }}
                                                </div>
                                            </td>
                                            
                                            <!-- Unit Price -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    Ks {{ number_format($item->unit_price, 1) }}
                                                </div>
                                            </td>
                                            
                                            <!-- Discount -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-red-600 dark:text-red-400">
                                                    Ks {{ number_format($item->discount ?? 0, 1) }}
                                                </div>
                                            </td>
                                            
                                            <!-- Total -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    Ks {{ number_format($item->total, 1) }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 dark:bg-gray-800/50">
                                    <tr>
                                        <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Total:
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                Ks {{ number_format($sale->items->sum('total'), 1) }}
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </x-common.component-card>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Quick Stats Card -->
                <x-common.component-card title="Sale Stats">
                    <div class="space-y-4">
                        <!-- Items Summary -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Items Summary</p>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Items</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $sale->items->sum('sale_qty') }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Unique Products</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $sale->items->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Average Item Value -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Average Item Value</p>
                            <div class="mt-2 rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Per Item</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    @php
                                        $avgValue = $sale->items->count() > 0 
                                            ? $sale->items->sum('total') / $sale->items->sum('sale_qty') 
                                            : 0;
                                    @endphp
                                    Ks {{ number_format($avgValue, 1) }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Sale Status -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Sale Status</p>
                            <div class="mt-2 rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Current</span>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </div>
                                @if($sale->status === 'completed')
                                    <p class="mt-1 text-xs text-gray-500">
                                        Completed on {{ $helper->formatDate($sale->updated_at, 'M d, Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-common.component-card>

                <!-- Notes Card -->
                @if($sale->note)
                    <x-common.component-card title="Notes">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400">
                                {{ nl2br(e($sale->note)) }}
                            </div>
                        </div>
                    </x-common.component-card>
                @endif

                <!-- Timestamps Card -->
                <x-common.component-card title="Timestamps">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Created</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $helper->formatDate($sale->created_at, 'M d, Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Updated</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $helper->formatDate($sale->updated_at, 'M d, Y g:i A') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Time</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $helper->formatDate($sale->created_at, 'h:i A') }}
                            </span>
                        </div>
                    </div>
                </x-common.component-card>

                <!-- Quick Actions Card -->
                <x-common.component-card title="Quick Actions">
                    <div class="space-y-2">
                        @if($sale->isPending())
                            <a href="{{ route('sales.edit', $sale->id) }}"
                                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Sale Details
                            </a>
                            
                            <form method="POST" action="{{ route('sales.complete', $sale->id) }}"
                                  onsubmit="return confirm('Mark this sale as completed?')">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400 dark:hover:bg-green-900/30 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                        
                        @if($sale->isPending())
                            <form method="POST" action="{{ route('sales.destroy', $sale->id) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this sale?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Sale
                                </button>
                            </form>
                        @endif
                        
                        <a href="#" target="_blank"
                            class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            Print Invoice
                        </a>
                        
                        <a href="{{ route('sales.index') }}"
                            class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to List
                        </a>
                    </div>
                </x-common.component-card>
            </div>
        </div>
    </div>
@endsection