@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Record Payment" :crumbs="[
        ['label' => 'Rents', 'url' => route('rents.index')],
        ['label' => 'Rent #' . $rent->rent_code, 'url' => route('rents.show', $rent->id)],
        ['label' => 'Record Payment'],
    ]" />

    <div class="grid grid-cols-1 gap-6">
        <x-common.component-card title="Record Payment for Rent #{{ $rent->rent_code }}">
            <!-- Rent Summary -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg dark:bg-gray-800">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Rent Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Customer:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">
                            {{ $rent->customer->name }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Total Amount:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">
                            Ks {{ number_format($rent->total, 1) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Paid Amount:</span>
                        <span class="font-medium text-gray-900 dark:text-white ml-2">
                            Ks {{ number_format($rent->total_paid, 1) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Due Amount:</span>
                        <span class="font-medium text-red-600 dark:text-red-400 ml-2">
                            Ks {{ number_format($dueAmount, 1) }}
                        </span>
                    </div>
                </div>
            </div>

            @if ($lastPayment)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900/20 dark:border-blue-800">
                    <h3 class="text-sm font-medium text-blue-900 dark:text-blue-200 mb-2">Last Payment Info</h3>
                    <div class="text-sm">
                        <span class="text-blue-700 dark:text-blue-300">Last payment:</span>
                        <span class="font-medium ml-2">Ks {{ number_format($lastPayment->amount, 1) }}</span>
                        <span class="mx-2">•</span>
                        <span class="text-blue-700 dark:text-blue-300">Date:</span>
                        <span class="font-medium ml-2">{{ $lastPayment->payment_date }}</span>
                        <span class="mx-2">•</span>
                        <span class="text-blue-700 dark:text-blue-300">Method:</span>
                        <span class="font-medium ml-2 capitalize">{{ $lastPayment->payment_method }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('rents.payments.store', $rent->id) }}" id="payment-form">
                @csrf

                <div class="space-y-6">
                    <!-- Amount Field with Full Amount Button -->
                    <div class="form-group">
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Payment Amount <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <div class="flex-1 relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                                    Ks
                                </span>
                                <input type="number" step="0.01" id="amount" name="amount"
                                    value="{{ old('amount') }}"
                                    class="w-full rounded-lg border border-gray-300 bg-white pl-10 pr-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                    required>
                            </div>
                            <button type="button" id="set-full-amount"
                                class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                Full Amount
                            </button>
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment For with options -->
                    <div class="form-group">
                        <label for="payment_for" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Payment For (Optional)
                        </label>
                        <select id="payment_for" name="payment_for"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="">Select Payment Type</option>
                            <option value="monthly"
                                {{ old('payment_for') == 'monthly' ? 'selected' : '' }}>First Month Rent</option>
                            <option value="deposit"
                                {{ old('payment_for') == 'deposit' ? 'selected' : '' }}>Security Deposit</option>
                            <option value="advance"
                                {{ old('payment_for') == 'advance' ? 'selected' : '' }}>Advance Payment</option>
                            <option value="final" {{ old('payment_for') == 'final' ? 'selected' : '' }}>
                                Final Payment</option>
                            <option value="penalty" {{ old('payment_for') == 'penalty' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_for')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Payment Method <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method" name="payment_method"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                            <option value="">Select Payment Method</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer</option>
                            <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit
                                Card</option>
                            <option value="mobile_payment"
                                {{ old('payment_method') == 'mobile_payment' ? 'selected' : '' }}>Mobile Payment</option>
                            <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque
                            </option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Date -->
                    <div>
                        <label for="payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Payment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="payment_date" name="payment_date"
                            value="{{ old('payment_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}"
                            class="period_date w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            required>
                        @error('payment_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Period (Optional) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="period_start"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Period Start (Optional)
                            </label>
                            <input type="date" id="period_start" name="period_start"
                                value="{{ old('period_start') }}"
                                class="period_date w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label for="period_end"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Period End (Optional)
                            </label>
                            <input type="date" id="period_end" name="period_end" value="{{ old('period_end') }}"
                                class="period_date w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                    </div>
                    @error('period_end')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <!-- Note -->
                    <div>
                        <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Notes (Optional)
                        </label>
                        <textarea id="note" name="note" rows="3"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            placeholder="Any additional notes about this payment...">{{ old('note') }}</textarea>
                        @error('note')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end gap-3">
                    <a href="{{ route('rents.show', $rent->id) }}"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Cancel
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-brand-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                        Record Payment
                    </button>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection
@push('scripts')
    <script>
        flatpickr(".period_date", {
            dateFormat: "Y-m-d",
            minDate: "{{ $rent->rent_date }}",
            defaultDate: "{{ date('Y-m-d') }}"
        });
    </script>
    <script>
        $(document).ready(function() {
            const today = new Date().toISOString().split('T')[0];

            // Set maximum dates to today
            $('#payment_date').attr('max', today);
            $('#period_start').attr('max', today);
            $('#period_end').attr('max', today);

            // Set full amount button
            $('#set-full-amount').on('click', function(e) {
                e.preventDefault();
                $('#amount').val(parseFloat({{ $dueAmount }}).toFixed(2));
            });

            // Auto-set period end when period start changes
            $('#period_start').on('change', function() {
                const periodStart = $(this).val();
                const periodEnd = $('#period_end');

                if (periodStart && !periodEnd.val()) {
                    // Calculate one month from start date
                    const date = new Date(periodStart);
                    date.setMonth(date.getMonth() + 1);
                    const nextMonth = date.toISOString().split('T')[0];

                    // If next month is in the future, set to today
                    if (nextMonth > today) {
                        periodEnd.val(today);
                    } else {
                        periodEnd.val(nextMonth);
                    }
                }
            });

            // Validate period dates
            $('#period_end').on('change', function() {
                const periodStart = $('#period_start').val();
                const periodEnd = $(this).val();

                if (periodStart && periodEnd && periodEnd < periodStart) {
                    alert('Period end date must be after period start date.');
                    $(this).val('');
                }
            });

            // Form submission validation
            $('#payment-form').on('submit', function(e) {
                const amount = parseFloat($('#amount').val()) || 0;

                if (amount <= 0) {
                    e.preventDefault();
                    alert('Please enter a valid payment amount.');
                    $('#amount').focus();
                    return false;
                }

                // Validate payment date is not in future
                const paymentDate = $('#payment_date').val();
                if (paymentDate > today) {
                    e.preventDefault();
                    alert('Payment date cannot be in the future.');
                    $('#payment_date').focus();
                    return false;
                }

                // Validate period dates
                const periodStart = $('#period_start').val();
                const periodEnd = $('#period_end').val();

                if (periodStart && periodEnd && periodEnd < periodStart) {
                    e.preventDefault();
                    alert('Period end date must be after period start date.');
                    $('#period_end').focus();
                    return false;
                }

                return true;
            });

            // Format amount on blur
            $('#amount').on('blur', function() {
                const value = parseFloat($(this).val());
                if (!isNaN(value) && value > 0) {
                    $(this).val(value.toFixed(2));
                }
            });

            // Show/hide period fields based on payment_for selection
            $('#payment_for').on('change', function() {
                const paymentFor = $(this).val().toLowerCase();
                const periodFields = $('#period_start, #period_end').closest('.form-group');

                if (paymentFor.includes('month') || paymentFor.includes('period')) {
                    periodFields.show();
                } else {
                    periodFields.hide();
                    $('#period_start, #period_end').val('');
                }
            });

            // Initialize period fields visibility
            const initialPaymentFor = $('#payment_for').val().toLowerCase();
            const periodFields = $('#period_start, #period_end').closest('.form-group');
            if (initialPaymentFor.includes('month') || initialPaymentFor.includes('period')) {
                periodFields.show();
            } else {
                periodFields.hide();
            }
        });
    </script>
@endpush
