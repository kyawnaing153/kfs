@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Rent" />

    <div class="grid grid-cols-1 gap-6">
        <x-common.component-card title="Edit Rent Information">
            <form method="POST" action="{{ route('rents.update', $rent->id) }}" id="rentForm">
                @csrf
                @method('PUT')
                
                <!-- Display Rent Code (Readonly) -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Rent Code
                    </label>
                    <input type="text" value="{{ $rent->rent_code }}" readonly
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 bg-gray-50">
                </div>

                <!-- Customer Selection -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Customer<span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $rent->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone_number ?? 'No phone' }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rent Date -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Rent Date<span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="rent_date" name="rent_date" value="{{ old('rent_date', $rent->rent_date) }}"
                        required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    @error('rent_date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Items Section - Mobile Friendly -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                            Rent Items
                        </h3>
                        <button type="button" id="addItemBtn"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-brand-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <!-- Mobile Items Container -->
                    <div id="itemsContainer" class="space-y-4">
                        <!-- Items will be dynamically added here -->
                    </div>

                    <!-- Status Warning (if rent has returns) -->
                    @if($rent->returns()->exists())
                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg dark:bg-yellow-900/20 dark:border-yellow-800">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                                <span class="text-sm text-yellow-700 dark:text-yellow-300">
                                    This rent has return records. Editing items may affect return calculations.
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Totals -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sub Total:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white" id="subTotal">${{ number_format($rent->sub_total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Pricing Section - Stack on mobile -->
                <div class="mb-6 space-y-4 md:space-y-0 md:grid md:grid-cols-3 md:gap-4">
                    <!-- Transport -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Transport Cost
                        </label>
                        <input type="number" name="transport" id="transport" value="{{ old('transport', $rent->transport) }}"
                            min="0" step="0.1"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <!-- Deposit -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Deposit
                        </label>
                        <input type="number" name="deposit" id="deposit" value="{{ old('deposit', $rent->deposit) }}" min="0"
                            step="0.1"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <!-- Total Paid -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Total Paid
                        </label>
                        <input type="number" name="total_paid" id="totalPaid" value="{{ old('total_paid', $rent->total_paid) }}" min="0"
                            step="0.1" readonly
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 bg-gray-50">
                        <p class="mt-1 text-xs text-gray-500">Total paid cannot be changed here. Use payment records.</p>
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Discount
                        </label>
                        <input type="number" name="discount" id="discount" value="{{ old('discount', $rent->discount) }}"
                            min="0" step="0.1"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <!-- Payment Type -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Payment Type
                        </label>
                        <select name="payment_type" id="payment_type"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">Select Payment Type</option>
                            <option value="cash" {{ old('payment_type', $rent->payment_type) == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ old('payment_type', $rent->payment_type) == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="bank_transfer" {{ old('payment_type', $rent->payment_type) == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer</option>
                            <option value="mobile_payment" {{ old('payment_type', $rent->payment_type) == 'mobile_payment' ? 'selected' : '' }}>
                                Mobile Payment</option>
                            <option value="credit" {{ old('payment_type', $rent->payment_type) == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                    </div>
                </div>

                <!-- Totals Display - Mobile Friendly -->
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-3 md:gap-4">
                        <div class="flex justify-between items-center md:block">
                            <p class="text-sm text-gray-600 dark:text-gray-400 md:mb-1">Sub Total</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white" id="displaySubTotal">${{ number_format($rent->sub_total, 2) }}</p>
                        </div>
                        <div class="flex justify-between items-center md:block">
                            <p class="text-sm text-gray-600 dark:text-gray-400 md:mb-1">Grand Total</p>
                            <p class="text-lg font-semibold text-green-600 dark:text-green-400" id="grandTotal">${{ number_format($rent->total, 2) }}</p>
                        </div>
                        <div class="flex justify-between items-center md:block">
                            <p class="text-sm text-gray-600 dark:text-gray-400 md:mb-1">Due Amount</p>
                            <p class="text-lg font-semibold text-red-600 dark:text-red-400" id="dueAmount">${{ number_format($rent->total_due, 2) }}</p>
                        </div>
                    </div>
                    <input type="hidden" name="sub_total" id="hiddenSubTotal" value="{{ $rent->sub_total }}">
                    <input type="hidden" name="total" id="hiddenTotal" value="{{ $rent->total }}">
                    <input type="hidden" name="total_due" id="hiddenTotalDue" value="{{ $rent->total_due }}">
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Notes
                    </label>
                    <textarea name="note" rows="3"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Any additional notes">{{ old('note', $rent->note) }}</textarea>
                </div>

                <!-- Submit Buttons - Stack on mobile -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-700 w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update Rent
                    </button>
                    <a href="{{ route('rents.show', $rent->id) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
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
        flatpickr("input[name='rent_date']", {
            dateFormat: "Y-m-d",
            defaultDate: "{{ $rent->rent_date }}"
        });

        // Initialize with existing rent items
        document.addEventListener('DOMContentLoaded', function() {
            const rentItems = @json($rent->items);
            console.log('Initial rent items:', rentItems);
            
            // Wait for RentCreator to initialize, then load existing items
            setTimeout(function() {
                if (window.rentCreator && rentItems.length > 0) {
                    // Clear the initial empty item
                    $('#itemsContainer').empty();
                    $('#desktopItemsContainer').empty();
                    window.rentCreator.itemCount = 0;
                    
                    // Add each existing item
                    rentItems.forEach(function(item, index) {
                        window.rentCreator.addItemCard();
                        
                        // Set values for the newly added item
                        const productSelect = $(`.product-select[data-index="${index}"]`);
                        const quantityInput = $(`.quantity-input[data-index="${index}"]`);
                        const priceInput = $(`.price-input[data-index="${index}"]`);
                        
                        // Set values
                        productSelect.val(item.product_variant_id).trigger('change');
                        quantityInput.val(item.rent_qty);
                        priceInput.val(item.unit_price);
                        
                        // Update totals
                        window.rentCreator.calculateItemTotal(index);
                    });
                    
                    // Calculate final totals
                    window.rentCreator.calculateTotals();
                }
            }, 500);
        });
    </script>
    
    <!-- Include the same create.js file but with edit mode enhancements -->
    <script src="{{ asset('Backend/js/rents/create.js') }}" defer></script>
    
    <!-- Add edit-specific JavaScript -->
    <script>
        // Override some behaviors for edit mode
        document.addEventListener('DOMContentLoaded', function() {
            // Disable total_paid field
            $('#totalPaid').prop('readonly', true);
            
            // Show warning if trying to change product with returns
            $(document).on('change', '.product-select', function() {
                const rentId = {{ $rent->id }};
                const hasReturns = {{ $rent->returns()->exists() ? 'true' : 'false' }};
                
                if (hasReturns && $(this).val()) {
                    // Check if this product was in the original rent
                    const originalProductId = $(this).data('original-product-id');
                    if (originalProductId && $(this).val() != originalProductId) {
                        if (confirm('Changing products after returns have been made may cause inconsistencies. Are you sure?')) {
                            return true;
                        } else {
                            $(this).val(originalProductId).trigger('change');
                            return false;
                        }
                    }
                }
            });
            
            // Store original product IDs for comparison
            const rentItems = @json($rent->items);
            rentItems.forEach(function(item, index) {
                $(`.product-select[data-index="${index}"]`).data('original-product-id', item.product_variant_id);
            });
        });
    </script>
@endpush

<style>
    /* Mobile-specific improvements */
    @media (max-width: 640px) {
        select,
        input,
        button,
        textarea {
            font-size: 16px !important;
        }
        
        .space-y-4>*+* {
            margin-top: 1rem;
        }
        
        .hidden-mobile {
            display: none !important;
        }
        
        .mobile-item-card {
            border-radius: 0.75rem;
            border-width: 1px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .mobile-stack {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
    }
    
    @supports (-webkit-touch-callout: none) {
        input[type="number"],
        input[type="tel"] {
            font-size: 16px;
        }
    }
    
    button,
    select,
    [role="button"] {
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
    }
    
    /* Style for read-only fields */
    input[readonly] {
        background-color: #f9fafb;
        cursor: not-allowed;
    }
    
    .dark input[readonly] {
        background-color: #374151;
    }
</style>