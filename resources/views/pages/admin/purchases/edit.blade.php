@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Create New Purchase" />

    <div class="grid grid-cols-1 gap-6">
        <x-common.component-card title="Purchase Information">

            <form method="POST" action="{{ route('purchases.update', $purchase->id) }}" id="purchaseForm">
                @csrf
                @method('PUT')

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Supplier -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Supplier<span class="text-red-500">*</span>
                        </label>

                        <select name="supplier_id" id="supplier_id" required
                            class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">
                            <option value="">Select Supplier</option>

                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $purchase->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }} - {{ $supplier->phone_number ?? 'No phone' }}
                                </option>
                            @endforeach

                        </select>

                        @error('supplier_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Purchase Date -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Purchase Date<span class="text-red-500">*</span>
                        </label>

                        <input type="date" name="purchase_date" id="purchase_date"
                            value="{{ old('purchase_date', $purchase->purchase_date) }}" required
                            class="h-11 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm">

                        @error('purchase_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <!-- Items -->
                <div class="mb-6">

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Purchase Items</h3>

                        <button type="button" id="addItemBtn"
                            class="bg-brand-600 text-white px-3 py-1.5 rounded-lg text-sm">
                            Add Item
                        </button>
                    </div>

                    <div id="itemsContainer" class="space-y-4"></div>

                </div>

                <!-- Pricing -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Transport -->
                    <div>
                        <label class="text-sm font-medium">Transport Cost</label>

                        <input type="number" name="transport" id="transport"
                            value="{{ old('transport', $purchase->transport) }}" min="0" step="1"
                            class="h-11 w-full rounded-lg border px-4">
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="text-sm font-medium">Discount</label>

                        <input type="number" name="discount" id="discount"
                            value="{{ old('discount', $purchase->discount) }}" min="0" step="0.1"
                            class="h-11 w-full rounded-lg border px-4">
                    </div>

                    <!-- Tax -->
                    <div>
                        <label class="text-sm font-medium">Tax</label>

                        <input type="number" name="tax" id="tax" value="{{ old('tax', $purchase->tax) }}"
                            min="0" step="1" class="h-11 w-full rounded-lg border px-4">
                    </div>

                </div>

                <!-- Payment -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">

                    <!-- Payment Status -->
                    <div>
                        <label class="text-sm font-medium">Payment Status</label>

                        <select name="payment_status" id="paymentStatus" class="h-11 w-full rounded-lg border px-4">

                            <option value="0"
                                {{ old('payment_status', $purchase->payment_status) == 0 ? 'selected' : '' }}>
                                Unpaid</option>

                            <option value="1"
                                {{ old('payment_status', $purchase->payment_status) == 1 ? 'selected' : '' }}>
                                Paid</option>

                        </select>
                    </div>

                    <!-- Delivery Status -->
                    <div>
                        <label class="text-sm font-medium">Delivery Status</label>

                        <select name="status" id="deliveryStatus" class="h-11 w-full rounded-lg border px-4">
                            <option value="0" {{ old('status', $purchase->status) == 0 ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ old('status', $purchase->status) == 1 ? 'selected' : '' }}>Delivered
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Totals -->
                <div class="mb-6 border rounded-lg p-4 bg-gray-50">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <p class="text-sm">Sub Total</p>
                            <p class="text-lg font-semibold" id="displaySubTotal">${{ number_format($purchase->sub_total, 0) }}</p>
                        </div>

                        <div>
                            <p class="text-sm">Grand Total</p>
                            <p class="text-lg font-semibold text-green-600" id="total_amount">${{ number_format($purchase->total_amount, 0) }}</p>
                        </div>
                    </div>

                    <input type="hidden" name="sub_total" id="hiddenSubTotal" value="{{ $purchase->sub_total }}">
                    <input type="hidden" name="total_amount" id="hiddenTotal" value="{{ $purchase->total_amount }}">
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="text-sm font-medium">Purchase Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-lg border px-4 py-2" placeholder="Any additional notes">{{ old('notes', $purchase->notes) }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">

                    <button type="submit" class="bg-brand-600 text-white px-4 py-2.5 rounded-lg text-sm">
                        Update Purchase
                    </button>

                    <a href="{{ route('purchases.index') }}" class="border px-4 py-2.5 rounded-lg text-sm">
                        Cancel
                    </a>

                </div>

            </form>

        </x-common.component-card>
    </div>
@endsection

@push('scripts')
    <script>
        flatpickr("input[name='purchase_date']", {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $purchase->purchase_date }}"
        });

        // Initialize with existing purchase items
        document.addEventListener('DOMContentLoaded', function() {
            const purchaseItems = @json($purchase->items);

            // Wait for PurchaseCreator to initialize, then load existing items
            setTimeout(function() {
                if (window.purchaseCreator && purchaseItems.length > 0) {
                    // Clear the initial empty item
                    $('#itemsContainer').empty();
                    window.purchaseCreator.itemCount = 0;

                    // Add each existing item
                    purchaseItems.forEach(function(item, index) {
                        window.purchaseCreator.addItemCard();

                        // Set values for the newly added item
                        const productSelect = $(`.product-select[data-index="${index}"]`);
                        const quantityInput = $(`.quantity-input[data-index="${index}"]`);
                        const priceInput = $(`.price-input[data-index="${index}"]`);

                        // Set values
                        productSelect.val(item.product_variant_id).trigger('change');
                        quantityInput.val(item.received_qty);
                        priceInput.val(item.unit_price);

                        // Update totals
                        window.purchaseCreator.calculateItemTotal(index);
                    });

                    // Calculate final totals
                    window.purchaseCreator.calculateTotals();
                }
            }, 500);
        });
    </script>
    <script src="{{ asset('Backend/js/purchases/create.js') }}" defer></script>
@endpush
