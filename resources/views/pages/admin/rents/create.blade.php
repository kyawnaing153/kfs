@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Create New Rent" />

    <div class="grid grid-cols-1 gap-6">
        <x-common.component-card title="Rent Information">
            <form method="POST" action="{{ route('rents.store') }}" id="rentForm">
                @csrf

                <!-- Customer Selection -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Customer<span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                    <input type="date" id="rent_date" name="rent_date" value="{{ old('rent_date', date('Y-m-d')) }}"
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
                        <!-- Dynamic item cards will be added here -->
                    </div>

                    <!-- Desktop Table (hidden on mobile) -->
                    {{-- <div class="hidden md:block overflow-x-auto mt-4">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="desktopItemsContainer">
                                <!-- Desktop rows will be added here -->
                            </tbody>
                        </table>
                    </div> --}}

                    <!-- Totals -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sub Total:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white" id="subTotal">$0.0</span>
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
                        <input type="number" name="transport" id="transport" value="{{ old('transport', 0) }}"
                            min="0" step="0.1"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <!-- Deposit -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Deposit
                        </label>
                        <input type="number" name="deposit" id="deposit" value="{{ old('deposit', 0) }}" min="0"
                            step="0.1"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <!-- Total Paid -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Total Paid
                        </label>
                        <input type="number" name="total_paid" id="totalPaid" value="{{ old('total_paid', 0) }}" min="0"
                            step="1"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Discount
                        </label>
                        <input type="number" name="discount" id="discount" value="{{ old('discount', 0) }}"
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
                            <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="card" {{ old('payment_type') == 'card' ? 'selected' : '' }}>Card</option>
                            <option value="bank_transfer" {{ old('payment_type') == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer</option>
                            <option value="mobile_payment" {{ old('payment_type') == 'mobile_payment' ? 'selected' : '' }}>
                                Mobile Payment</option>
                            <option value="credit" {{ old('payment_type') == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                    </div>
                </div>

                <!-- Totals Display - Mobile Friendly -->
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="space-y-3 md:space-y-0 md:grid md:grid-cols-3 md:gap-4">
                        <div class="flex justify-between items-center md:block">
                            <p class="text-sm text-gray-600 dark:text-gray-400 md:mb-1">Sub Total</p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white" id="displaySubTotal">$0.0</p>
                        </div>
                        <div class="flex justify-between items-center md:block">
                            <p class="text-sm text-gray-600 dark:text-gray-400 md:mb-1">Grand Total</p>
                            <p class="text-lg font-semibold text-green-600 dark:text-green-400" id="grandTotal">$0.0</p>
                        </div>
                        <div class="flex justify-between items-center md:block">
                            <p class="text-sm text-gray-600 dark:text-gray-400 md:mb-1">Due Amount</p>
                            <p class="text-lg font-semibold text-red-600 dark:text-red-400" id="dueAmount">$0.0</p>
                        </div>
                    </div>
                    <input type="hidden" name="sub_total" id="hiddenSubTotal" value="0">
                    <input type="hidden" name="total" id="hiddenTotal" value="0">
                    <input type="hidden" name="total_due" id="hiddenTotalDue" value="0">
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Notes
                    </label>
                    <textarea name="note" rows="3"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Any additional notes">{{ old('note') }}</textarea>
                </div>

                <!-- Submit Buttons - Stack on mobile -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-700 w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Create Rent
                    </button>
                    <a href="{{ route('rents.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 w-full sm:w-auto">
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
        flatpickr("input[name='rent_date']", {
            dateFormat: "Y-m-d",
            defaultDate: "{{ date('Y-m-d') }}"
        });
    </script>
    <!-- Include external JS file -->
    <script src="{{ asset('Backend/js/rents/create.js') }}" defer></script>
@endpush

<style>
    /* Mobile-specific improvements */
    @media (max-width: 640px) {

        /* Larger touch targets */
        select,
        input,
        button,
        textarea {
            font-size: 16px !important;
            /* Prevents iOS zoom on focus */
        }

        /* Better spacing for mobile */
        .space-y-4>*+* {
            margin-top: 1rem;
        }

        /* Hide desktop table on mobile */
        .hidden-mobile {
            display: none !important;
        }

        /* Card styling for mobile */
        .mobile-item-card {
            border-radius: 0.75rem;
            border-width: 1px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        /* Stack form elements vertically on mobile */
        .mobile-stack {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
    }

    /* Prevent zoom on iOS for number inputs */
    @supports (-webkit-touch-callout: none) {

        input[type="number"],
        input[type="tel"] {
            font-size: 16px;
        }
    }

    /* Better touch feedback */
    button,
    select,
    [role="button"] {
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
    }
</style>
