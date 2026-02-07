@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Process Return - ' . $rent->rent_code" />

    <div class="grid grid-cols-1 gap-6">
        <x-common.component-card title="Process Return">
            <div class="mb-6 rounded-lg border border-gray-200 bg-blue-50 p-4 dark:border-gray-700 dark:bg-blue-900/20">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                            Processing Return for Rent #{{ $rent->rent_code }}
                        </h4>
                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                            Customer: {{ $rent->customer->name }} |
                            Rent Date: {{ $rent->rent_date }} |
                            Status: <span class="font-medium">{{ ucfirst($rent->status) }} |
                                Deposit: ${{ number_format($rent->deposit, 0) }} |
                                Daily Rental Subtotal : ${{ number_format($rent->sub_total, 0) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('rents.returns.store', $rent->id) }}" enctype="multipart/form-data"
                id="returnForm">
                @csrf

                <input type="hidden" name="selected_items" id="selectedItems" value="">
                <input type="hidden" name="total_days" id="totalDays" value="">
                <!-- Basic Information -->
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Return Date -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Return Date<span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="return_date" value="{{ old('return_date', date('Y-m-d')) }}"
                            min="{{ $rent->rent_date }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                            required>
                        @error('return_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Return Status<span class="text-red-500">*</span>
                        </label>
                        <select name="status"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                            required>
                            <option value="partial" {{ old('status', 'partial') == 'partial' ? 'selected' : '' }}>
                                Partial Return
                            </option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                Completed Return
                            </option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Items to Return -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                            Items to Return
                        </h3>
                        <div class="text-sm text-gray-500">
                            <span id="selectedItemsCount">0</span> item(s) selected
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                        <input type="checkbox" id="selectAllItems" class="rounded border-gray-300">
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rented</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remaining
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Return Qty
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Damage Fee
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700" id="itemsTableBody">
                                @forelse($remainingItems as $item)
                                    @php
                                        $remainingQty = $item->rent_qty - $item->returned_qty;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <!-- Checkbox -->
                                        <td class="px-4 py-3">
                                            <input type="checkbox" class="item-checkbox rounded border-gray-300"
                                                data-item-id="{{ $item->id }}" data-max-qty="{{ $remainingQty }}">
                                        </td>

                                        <!-- Product -->
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                {{-- @if ($item->productVariant->product->thumb_url)
                                                    <img src="{{ $item->productVariant->product->thumb_url }}"
                                                        alt="{{ $item->productVariant->product->product_name }}"
                                                        class="h-10 w-10 rounded-lg object-cover">
                                                @endif --}}
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        {{ $item->productVariant->product->product_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $item->productVariant->size ?: 'Standard' }}
                                                        @if ($item->unit)
                                                            <span class="text-xs">({{ $item->unit }})</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Rented Qty -->
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $item->rent_qty }}
                                            </div>
                                        </td>

                                        <!-- Returned Qty -->
                                        <td class="px-4 py-3">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $item->returned_qty }}
                                            </div>
                                        </td>

                                        <!-- Remaining Qty -->
                                        <td class="px-4 py-3">
                                            <div
                                                class="text-sm font-medium {{ $remainingQty > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400' }}">
                                                {{ $remainingQty }}
                                            </div>
                                        </td>

                                        <!-- Return Quantity -->
                                        <td class="px-4 py-3">
                                            <input type="number" name="items[{{ $loop->index }}][qty]"
                                                class="return-qty dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                                min="0" max="{{ $remainingQty }}" value="0"
                                                data-item-id="{{ $item->id }}" disabled>
                                            <input type="hidden" name="items[{{ $loop->index }}][rent_item_id]"
                                                value="{{ $item->id }}">
                                        </td>

                                        <!-- Damage Fee -->
                                        <td class="px-4 py-3">
                                            <input type="number" name="items[{{ $loop->index }}][damage_fee]"
                                                class="damage-fee dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                                min="0" step="1" value="0"
                                                data-item-id="{{ $item->id }}" disabled>
                                        </td>

                                        <!-- Note -->
                                        <td class="px-4 py-3">
                                            <input type="text" name="items[{{ $loop->index }}][note]"
                                                class="item-note dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                                placeholder="Condition notes" data-item-id="{{ $item->id }}" disabled>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                            </svg>
                                            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">All items
                                                returned</h3>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                                All items for this rent have been fully returned.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <h4 class="mb-4 text-sm font-medium text-gray-700 dark:text-gray-300">Financial Summary</h4>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Transport Amount -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Transport Amount
                            </label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">$</span>
                                <input type="number" name="transport" id="transport" value="{{ old('transport', 0) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    min="0" step="1" required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Transport cost while rents ($
                                {{ number_format($rent->transport, 0) }})</p>
                            @error('transport')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Refund Amount -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Refund Amount
                            </label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">$</span>
                                <input type="number" name="refund_amount" id="refund_amount"
                                    value="{{ old('refund_amount', 0) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    min="0" step="1" required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Amount to refund to customer (e.g., for damages)</p>
                            @error('refund_amount')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Collect Amount -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Collect Amount
                            </label>
                            <div class="flex items-center">
                                <span class="mr-2 text-gray-500">$</span>
                                <input type="number" name="collect_amount" id="collect_amount"
                                    value="{{ old('collect_amount', 0) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    min="0" step="1" required>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Amount to collect from customer (e.g., late fees)</p>
                            @error('collect_amount')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Auto-calculated Damage Fee -->
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Total Damage Fees:</span>
                            <span class="font-medium text-gray-900 dark:text-white" id="totalDamageFee">$0.0</span>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Auto-calculated from item damage fees above</p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Additional Notes
                    </label>
                    <textarea name="note" rows="3"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Any additional notes about this return">{{ old('note') }}</textarea>
                    @error('note')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Return Image -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Return Condition Image (Optional)
                    </label>
                    <input type="file" name="return_image" id="return_image" accept="image/*"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    <p class="mt-1 text-xs text-gray-500">Max file size: 2MB. Allowed: JPG, PNG, GIF, WebP</p>
                    @error('return_image')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Image Preview -->
                    <div id="imagePreview" class="mt-2 hidden">
                        <img id="previewImage" class="h-32 rounded-lg border border-gray-300 dark:border-gray-700">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Process Return
                    </button>
                    <a href="{{ route('rents.show', $rent->id) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection

@push('scripts')
    
    <script>
        flatpickr("input[name='return_date']", {
            dateFormat: "Y-m-d",
            minDate: "{{ $rent->rent_date }}",
            defaultDate: "{{ date('Y-m-d') }}"
        });
    </script>
    <script>
        // Define global rentData variable
        window.rentData = {
            rentDate: '{{ $rent->rent_date }}',
            deposit: {{ $rent->deposit }},
            subTotal: {{ $rent->sub_total }},
            totalPayment: {{ $totalPaymentByRentId ?? 0 }},
        };
    </script>
    <script src="{{ asset('Backend/js/rents/rent-return-process.js') }}" defer></script>
@endpush

<style>
    #calculationSummary {
        transition: all 0.3s ease;
    }

    #calculationSummary .balance-positive {
        color: #10b981;
    }

    #calculationSummary .balance-negative {
        color: #ef4444;
    }
</style>
