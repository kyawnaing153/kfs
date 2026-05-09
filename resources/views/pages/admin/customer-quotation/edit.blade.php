@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb :pageTitle="'Edit Quotation: ' . $quotation->quotation_code" />

    <div class="space-y-6">
        <form method="POST" action="{{ route('customer-quotation.update', $quotation->id) }}" id="quotationForm"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Main Form Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                            Edit Quotation
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Update quotation details, items, and financial information
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex gap-2">
                        <a href="{{ route('customer-quotation.index') }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Customer Information (Read-only) -->
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-3">Customer Information</h4>
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center dark:bg-blue-900">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $quotation->customer->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $quotation->customer->email }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $quotation->customer->phone_number ?? 'No phone' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status and Dates -->
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-3">Status & Dates</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Quotation Status *
                                    </label>
                                    <select name="status" required
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                        <option value="submitted" {{ $quotation->status == 'submitted' ? 'selected' : '' }}>
                                            Submitted</option>
                                        <option value="approved" {{ $quotation->status == 'approved' ? 'selected' : '' }}>
                                            Approved</option>
                                        <option value="rejected" {{ $quotation->status == 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Quotation Date
                                        </label>
                                        <input type="date"
                                            value="{{ $quotation->quotation_date ? date('Y-m-d', strtotime($quotation->quotation_date)) : '' }}"
                                            disabled
                                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 dark:bg-gray-800 dark:text-gray-400">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Quotation Code
                                        </label>
                                        <input type="text" value="{{ $quotation->quotation_code }}" disabled
                                            class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 dark:bg-gray-800 dark:text-gray-400">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rent Information -->
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-3">Rent Information</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Rent Date
                                    </label>
                                    <input type="date" name="rent_date"
                                        value="{{ $quotation->rent_date ? date('Y-m-d', strtotime($quotation->rent_date)) : '' }}"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Rent Duration (days)
                                    </label>
                                    <input type="number" name="rent_duration" value="{{ $quotation->rent_duration }}"
                                        min="1"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Financial Adjustments -->
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-3">Financial Adjustments</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Deposit Amount (Ks)
                                    </label>
                                    <input type="number" name="deposit" value="{{ $quotation->deposit }}" step="1"
                                        min="0"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    <p class="mt-1 text-xs text-gray-500">Security deposit required from customer</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Discount (Ks)
                                    </label>
                                    <input type="number" name="discount" value="{{ $quotation->discount }}" step="1"
                                        min="0"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                </div>
                            </div>
                        </div>

                        <!-- Transport Information -->
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-md font-semibold text-gray-800 dark:text-white">Transport Information</h4>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="transport_required" value="1"
                                        {{ $quotation->transport_required ? 'checked' : '' }} class="sr-only peer"
                                        id="transport_required">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                    <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Transport
                                        Required</span>
                                </label>
                            </div>

                            <div id="transportFields"
                                style="{{ $quotation->transport_required ? '' : 'display: none;' }}">
                                <div class="space-y-4 mt-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Transport Fee (Ks)
                                        </label>
                                        <input type="number" name="transport" value="{{ $quotation->transport }}"
                                            step="1" min="0"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Transport Address
                                        </label>
                                        <textarea name="transport_address" rows="3"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                            placeholder="Enter delivery address">{{ $quotation->transport_address }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                            <h4 class="text-md font-semibold text-gray-800 dark:text-white mb-3">Notes</h4>
                            <textarea name="notes" rows="4"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                placeholder="Add any additional notes or comments...">{{ $quotation->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quotation Items Section -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mt-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="itemsContainer" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($quotation->items as $item)
                                <tr data-item-id="{{ $item->id }}">
                                    <td class="px-4 py-3">
                                        <input type="hidden" name="items[{{ $loop->index }}][id]"
                                            value="{{ $item->id }}">
                                        <input type="hidden" name="items[{{ $loop->index }}][product_variant_id]"
                                            value="{{ $item->product_variant_id }}">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $item->productVariant->product->product_name ?? 'Product not found' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if ($item->productVariant->size)
                                                Size: {{ $item->productVariant->size }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="items[{{ $loop->index }}][qty]"
                                            value="{{ $item->qty }}" min="1" step="1"
                                            class="item-qty w-20 rounded-lg border border-gray-300 px-2 py-1 text-center focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                            data-index="{{ $loop->index }}">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" name="items[{{ $loop->index }}][unit]"
                                            value="{{ $item->unit }}"
                                            class="w-24 rounded-lg border border-gray-300 px-2 py-1 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="items[{{ $loop->index }}][unit_price]"
                                            value="{{ $item->unit_price }}" step="0.01" min="0"
                                            class="item-price w-28 rounded-lg border border-gray-300 px-2 py-1 text-right focus:border-blue-500 focus:ring-1 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                            data-index="{{ $loop->index }}">
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="item-total font-medium text-gray-900 dark:text-white">
                                            Ks {{ number_format($item->total, 0) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" onclick="removeItem({{ $item->id }}, this)"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <td colspan="4"
                                    class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">
                                    Sub Total:
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span id="subTotalDisplay" class="font-semibold text-gray-900 dark:text-white">
                                        Ks {{ number_format($quotation->sub_total, 0) }}
                                    </span>
                                </td>
                                <td></td>
                            </tr>
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td colspan="4"
                                    class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300">
                                    Total:
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span id="totalDisplay" class="text-lg font-bold text-green-600 dark:text-green-400">
                                        Ks {{ number_format($quotation->total, 0) }}
                                    </span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Hidden inputs for removed items -->
            <div id="removedItemsInput"></div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        let removedItems = [];

        // Toggle transport fields
        document.getElementById('transport_required')?.addEventListener('change', function() {
            const transportFields = document.getElementById('transportFields');
            if (this.checked) {
                transportFields.style.display = 'block';
            } else {
                transportFields.style.display = 'none';
                document.querySelector('input[name="transport"]').value = 0;
                document.querySelector('textarea[name="transport_address"]').value = '';
            }
            recalculateTotals();
        });

        // Remove item function
        function removeItem(itemId, button) {
            if (confirm('Are you sure you want to remove this item?')) {
                removedItems.push(itemId);
                updateRemovedItemsInput();

                const row = button.closest('tr');
                row.remove();
                recalculateTotals();
            }
        }

        // Update hidden inputs for removed items
        function updateRemovedItemsInput() {
            let html = '';
            removedItems.forEach(id => {
                html += `<input type="hidden" name="items_to_remove[]" value="${id}">`;
            });
            document.getElementById('removedItemsInput').innerHTML = html;
        }

        // Update item total when quantity or price changes
        function updateItemTotal(element) {
            const row = element.closest('tr');
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = qty * price;

            row.querySelector('.item-total').textContent = `Ks ${total.toFixed(0)}`;
            recalculateTotals();
        }

        // Recalculate subtotal and total
        function recalculateTotals() {
            let subTotal = 0;
            const items = document.querySelectorAll('#itemsContainer tr');

            items.forEach(item => {
                const totalText = item.querySelector('.item-total')?.textContent;
                if (totalText) {
                    const totalValue = parseFloat(totalText.replace('Ks', '').replace(',', '')) || 0;
                    subTotal += totalValue;
                }
            });

            const deposit = parseFloat(document.querySelector('input[name="deposit"]')?.value) || 0;
            const transport = parseFloat(document.querySelector('input[name="transport"]')?.value) || 0;
            const discount = parseFloat(document.querySelector('input[name="discount"]')?.value) || 0;
            const transportRequired = document.querySelector('input[name="transport_required"]')?.checked || false;

            let total = deposit;
            if (transportRequired) {
                total += transport;
            }
            total -= discount;

            document.getElementById('subTotalDisplay').textContent = `Ks ${subTotal.toFixed(0)}`;
            document.getElementById('totalDisplay').textContent = `Ks ${Math.max(0, total).toFixed(0)}`;
        }

        // Add event listeners for item quantity and price changes
        document.querySelectorAll('.item-qty, .item-price').forEach(input => {
            input.addEventListener('change', function() {
                updateItemTotal(this);
            });
        });

        // Add event listeners for financial fields
        document.querySelectorAll('input[name="deposit"], input[name="transport"], input[name="discount"]').forEach(
        input => {
            input.addEventListener('change', recalculateTotals);
            input.addEventListener('keyup', recalculateTotals);
        });

        // Form validation before submit
        document.getElementById('quotationForm')?.addEventListener('submit', function(e) {
            const items = document.querySelectorAll('#itemsContainer tr');
            if (items.length === 0) {
                e.preventDefault();
                alert('Please ensure at least one item remains in the quotation.');
                return false;
            }
        });

        // Initial calculation
        recalculateTotals();
    </script>
@endpush
