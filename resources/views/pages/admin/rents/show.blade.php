@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Rent Details: ' . $rent->rent_code" />

    <div class="space-y-6">
        <!-- Rent Summary Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                <!-- Rent Info -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-start md:gap-6">
                        <!-- Rent Code & Status -->
                        <div class="mb-4 md:mb-0">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $rent->rent_code }}
                            </h1>
                            
                            <div class="flex flex-wrap gap-2">
                                <!-- Status Badge -->
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                        'ongoing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$rent->status] ?? $statusColors['pending'] }}">
                                    {{ ucfirst($rent->status) }}
                                </span>
                                
                                <!-- Payment Type -->
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                    {{ ucfirst(str_replace('_', ' ', $rent->payment_type)) }}
                                </span>
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
                                        {{ $rent->customer->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $rent->customer->phone_number ?? 'No phone' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rent Dates -->
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Rent Date</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $rent->rent_date }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Created</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $rent->created_at }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-4 flex flex-wrap gap-2 md:mt-0 md:flex-col md:items-end">
                    @if($rent->status === 'pending')
                        <a href="{{ route('rents.edit', $rent->id) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-brand-300 bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 dark:border-brand-700 dark:bg-brand-600 dark:hover:bg-brand-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Rent
                        </a>
                    @endif
                    
                    @if($rent->status !== 'completed')
                        <a href="{{ route('rents.returns.create', $rent->id) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                            </svg>
                            Return Items
                        </a>
                        
                        <a href="{{ route('rents.payments.create', $rent->id) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm font-medium text-green-700 hover:bg-green-100 dark:border-green-800 dark:bg-green-900/30 dark:text-green-400 dark:hover:bg-green-900/50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Record Payment
                        </a>
                    @endif
                    
                    <a href="{{ route('rents.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Rents
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Column: Rent Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Financial Summary Card -->
                <x-common.component-card title="Financial Summary">
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <!-- Sub Total -->
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Daily Rental Sub Total</p>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                ${{ number_format($rent->sub_total, 0) }}
                            </p>
                        </div>
                        
                        <!-- Deposit -->
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Deposit</p>
                            <p class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                                +${{ number_format($rent->deposit, 0) }}
                            </p>
                        </div>

                        <!-- Transport -->
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Transport</p>
                            <p class="text-xl font-semibold text-gray-800 dark:text-white">
                                +${{ number_format($rent->transport, 1) }}
                            </p>
                        </div>

                        <!-- Total -->
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount</p>
                            <p class="text-xl font-semibold text-green-600 dark:text-green-400">
                                ${{ number_format($rent->total, 1) }}
                            </p>
                        </div>
   
                    </div>
                    
                    <!-- Payment Summary -->
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <!-- Discount -->
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Discount</p>
                            <p class="text-xl font-semibold text-red-600 dark:text-red-400">
                                -${{ number_format($rent->discount, 0) }}
                            </p>
                        </div>

                        <!-- Total Paid -->
                        <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                            <p class="text-xl font-semibold text-green-600 dark:text-green-400">
                                ${{ number_format($rent->total_paid, 1) }}
                            </p>
                        </div>
                        
                        <!-- Total Due -->
                        <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Due</p>
                            <p class="text-xl font-semibold text-red-600 dark:text-red-400">
                                ${{ number_format($rent->total_due, 1) }}
                            </p>
                        </div>

                        <!-- Payment Total -->
                        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-800 dark:bg-blue-900/20">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Payment</p>
                            <p class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                                +${{ number_format($rent->payments->sum('amount'), 0) }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500">
                                {{ $rent->payments->count() }} payment(s)
                            </p>
                        </div>
                    </div>


                </x-common.component-card>

                <!-- Rent Items Card -->
                <x-common.component-card title="Rented Items">
                    @if($rent->items->isEmpty())
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No items rented</h3>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <th class="px-4 py-3 min-w-[180px] text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($rent->items as $item)
                                        @php
                                            $returnedPercentage = $item->rent_qty > 0 
                                                ? round(($item->returned_qty / $item->rent_qty) * 100, 0)
                                                : 0;
                                            $isFullyReturned = $item->returned_qty >= $item->rent_qty;
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <!-- Product -->
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if($item->productVariant->product->thumb_url)
                                                        <img src="{{ $item->productVariant->product->thumb_url }}" 
                                                             alt="{{ $item->productVariant->product->product_name }}"
                                                             class="h-10 w-10 rounded-lg object-cover">
                                                    @endif
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            {{ $item->productVariant->product->product_name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $item->productVariant->size ?: 'Standard' }}
                                                            @if($item->unit)
                                                                <span class="text-xs">({{ $item->unit }})</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            SKU: {{ $item->productVariant->sku }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            
                                            <!-- Quantity -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $item->rent_qty }}
                                                </div>
                                            </td>
                                            
                                            <!-- Unit Price -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    ${{ number_format($item->unit_price, 1) }}
                                                </div>
                                            </td>
                                            
                                            <!-- Total -->
                                            <td class="px-4 py-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    ${{ number_format($item->total, 1) }}
                                                </div>
                                            </td>
                                            
                                            <!-- Returned -->
                                            <td class="px-4 py-4">
                                                <div class="space-y-2">
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-gray-600 dark:text-gray-400">
                                                            {{ $item->returned_qty }}/{{ $item->rent_qty }}
                                                        </span>
                                                        <span class="font-medium {{ $isFullyReturned ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                                            {{ $returnedPercentage }}%
                                                        </span>
                                                    </div>
                                                    <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                                                        <div class="h-full rounded-full {{ $isFullyReturned ? 'bg-green-500' : 'bg-yellow-500' }}" 
                                                             style="width: {{ min($returnedPercentage, 100) }}%">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 dark:bg-gray-800/50">
                                        <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Total:
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                ${{ number_format($rent->items->sum('total'), 1) }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm">
                                                <span class="text-gray-600 dark:text-gray-400">
                                                    {{ $rent->items->sum('returned_qty') }}/{{ $rent->items->sum('rent_qty') }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </x-common.component-card>

                <!-- Returns History Card -->
                @if($rent->returns->isNotEmpty())
                    <x-common.component-card title="Return History">
                        <div class="space-y-4">
                            @foreach($rent->returns as $return)
                                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                Return #{{ $return->id }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $return->return_date }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $return->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                            {{ ucfirst($return->status) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Return Items -->
                                    <div class="mb-3 space-y-2">
                                        @foreach($return->items as $returnItem)
                                            <div class="flex items-center justify-between text-sm">
                                                <div class="text-gray-600 dark:text-gray-400">
                                                    {{ $returnItem->rentItem->productVariant->product->product_name }} - 
                                                    {{ $returnItem->rentItem->productVariant->size ?: 'Standard' }}
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-gray-900 dark:text-white">
                                                        {{ $returnItem->qty }} returned
                                                    </span>
                                                    @if($returnItem->damage_fee > 0)
                                                        <span class="text-red-600 dark:text-red-400">
                                                            +${{ number_format($returnItem->damage_fee, 1) }} damage
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Return Summary -->
                                    <div class="flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-700">
                                        <div class="text-sm">
                                            @if($return->refund_amount > 0)
                                                <span class="text-green-600 dark:text-green-400">
                                                    Refund: ${{ number_format($return->refund_amount, 1) }}
                                                </span>
                                            @endif
                                            @if($return->collect_amount > 0)
                                                <span class="text-red-600 dark:text-red-400 ml-3">
                                                    Collect: ${{ number_format($return->collect_amount, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($return->return_image)
                                            <a href="{{ asset('storage/' . $return->return_image) }}" 
                                               target="_blank"
                                               class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Image
                                            </a>
                                        @endif
                                    </div>
                                    
                                    @if($return->note)
                                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $return->note }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </x-common.component-card>
                @endif
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <!-- Payment History Card -->
                <x-common.component-card title="Payment History">
                    @if($rent->payments->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm py-4 text-center">No payments recorded</p>
                    @else
                        <div class="space-y-3">
                            @foreach($rent->payments as $payment)
                                <div class="rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                ${{ number_format($payment->amount, 1) }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $payment->payment_date }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                                {{ $payment->payment_for === 'monthly' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                                                   ($payment->payment_for === 'final' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                                   'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                                {{ ucfirst($payment->payment_for) }}
                                            </span>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ ucfirst($payment->payment_method) }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($payment->period_start && $payment->period_end)
                                        <div class="mt-2 text-xs text-gray-500">
                                            Period: {{ $payment->period_start }} - {{ $payment->period_end }}
                                        </div>
                                    @endif
                                    @if($payment->note)
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ Str::limit($payment->note, 50) }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Payment Summary -->
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Paid:</span>
                                <span class="text-lg font-semibold text-green-600 dark:text-green-400">
                                    ${{ number_format($rent->payments->sum('amount'), 1) }}
                                </span>
                            </div>
                        </div>
                    @endif
                </x-common.component-card>

                <!-- Quick Stats Card -->
                <x-common.component-card title="Rent Stats">
                    <div class="space-y-4">
                        <!-- Items Summary -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Items Summary</p>
                            <div class="mt-1 grid grid-cols-2 gap-2">
                                <div class="rounded-lg bg-gray-50 p-2 dark:bg-gray-800">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Items</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $rent->items->sum('rent_qty') }}
                                    </p>
                                </div>
                                <div class="rounded-lg bg-gray-50 p-2 dark:bg-gray-800">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Returned</p>
                                    <p class="text-lg font-semibold {{ $rent->items->sum('returned_qty') >= $rent->items->sum('rent_qty') ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                        {{ $rent->items->sum('returned_qty') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Progress -->
                        {{-- <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Payment Progress</p>
                            <div class="mt-2">
                                @php
                                    $paymentPercentage = $rent->total > 0 ? round(($rent->total_paid / $rent->total) * 100, 0) : 0;
                                @endphp
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">Progress</span>
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
                        </div> --}}
                        
                        <!-- Return Progress -->
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Return Progress</p>
                            <div class="mt-2">
                                @php
                                    $totalRented = $rent->items->sum('rent_qty');
                                    $totalReturned = $rent->items->sum('returned_qty');
                                    $returnPercentage = $totalRented > 0 ? round(($totalReturned / $totalRented) * 100, 0) : 0;
                                @endphp
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                    <span class="font-medium {{ $returnPercentage >= 100 ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                        {{ $returnPercentage }}%
                                    </span>
                                </div>
                                <div class="h-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div class="h-full rounded-full {{ $returnPercentage >= 100 ? 'bg-green-500' : 'bg-yellow-500' }}" 
                                         style="width: {{ min($returnPercentage, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-common.component-card>

                <!-- Document Card -->
                @if($rent->document)
                    <x-common.component-card title="Attached Document">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            Rent Document
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Uploaded: {{ $rent->created_at }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $rent->document) }}" 
                                   target="_blank"
                                   class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    </x-common.component-card>
                @endif

                <!-- Notes Card -->
                @if($rent->note)
                    <x-common.component-card title="Notes">
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="prose prose-sm max-w-none text-gray-600 dark:text-gray-400">
                                {{ nl2br(e($rent->note)) }}
                            </div>
                        </div>
                    </x-common.component-card>
                @endif

                <!-- Quick Actions Card -->
                <x-common.component-card title="Quick Actions">
                    <div class="space-y-2">
                        @if($rent->status === 'pending')
                            <a href="{{ route('rents.edit', $rent->id) }}"
                                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Rent Details
                            </a>
                            
                            <form method="POST" action="{{ route('rents.destroy', $rent->id) }}"
                                  onsubmit="return confirm('Are you sure you want to cancel this rent?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 transition-colors">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Cancel Rent
                                </button>
                            </form>
                        @endif
                        
                        @if($rent->status !== 'completed')
                            <a href="{{ route('rents.returns.create', $rent->id) }}"
                                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
                                </svg>
                                Process Return
                            </a>
                            
                            <a href="{{ route('rents.payments.create', $rent->id) }}"
                                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Record Payment
                            </a>
                        @endif
                        
                        <a href="{{ route('rents.index') }}"
                            class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back
                        </a>
                    </div>
                </x-common.component-card>
            </div>
        </div>
    </div>
@endsection