@extends('layouts.app')
<style>
    @media print {
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .invoice-container {
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .print\:hidden {
            display: none !important;
        }

        /* Hide all navigation and dashboard elements */
        nav,
        aside,
        header,
        footer,
        .navbar,
        .sidebar,
        .main-header,
        .sticky-header,
        [role="navigation"],
        .fixed,
        .sticky {
            display: none !important;
        }

        /* Hide the main layout wrapper classes */
        .min-h-screen,
        .bg-gray-100 {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Make the invoice take full page */
        main {
            margin: 0 !important;
            padding: 0 !important;
        }

        .py-6 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .mx-auto {
            margin: 0 !important;
            max-width: 100% !important;
        }

        .custom {
            margin-top: 1rem !important;
        }

        .sm\:px-6,
        .lg\:px-8 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        @page {
            size: A4;
            margin: 2.5mm 3mm 2.5mm 3mm;
        }
    }

    /* For screen view */
    .invoice-container {
        margin-top: 20px;
    }
</style>

@section('content')
    <div class="invoice-container bg-white shadow-lg p-10">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4 pb-6 border-b-2 border-blue-700">
            <div class="company-info">
                <h1 class="text-blue-700 text-2xl font-bold mb-1">Kyaw Family Scaffolding</h1>
                <p class="text-gray-600 text-sm">123 Construction Street, Yangon, Myanmar</p>
                <p class="text-gray-600 text-sm">Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com</p>
            </div>
            <div class="invoice-details text-right">
                <h2 class="text-blue-700 text-2xl font-bold mb-1">RETURN RECEIPT</h2>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Receipt No:</span>
                    RETURN-{{ $return->id }}</p>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Rent No:</span>
                    {{ $rent->rent_code }}</p>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Print Date:</span>
                    {{ $return->current_time }}</p>

            </div>
        </div>

        <!-- Client Section -->
        <div class="flex justify-between items-center mb-4">
            <div class="bill-to w-[48%]">
                <div class="text-blue-700 font-bold text-sm uppercase mb-2">Customer Information:</div>
                <div class="text-gray-600 text-sm">
                    <p class="font-semibold text-gray-800">
                        {{ $rent->customer->name ?? '' }}
                    </p>
                    @if ($rent->customer->company_name ?? false)
                        <p>{{ $rent->customer->company_name }}</p>
                    @endif
                    <p>Phone: {{ $rent->customer->phone_number ?? '' }}</p>
                    @if ($rent->customer->email ?? false)
                        <p>Email: {{ $rent->customer->email }}</p>
                    @endif
                </div>
            </div>
            <div class="rent-info w-[48%] lg:ml-[60%]">
                <div class="text-blue-700 font-bold text-sm uppercase mb-2">Rental Period:</div>
                <div class="text-gray-600 text-sm">
                    <p><span class="font-semibold text-gray-800">From:</span>
                        {{ \Carbon\Carbon::parse($rent->rent_date)->format('Y-m-d') }}</p>
                    <p><span class="font-semibold text-gray-800">To:</span>
                        {{ \Carbon\Carbon::parse($return->return_date)->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>

        <!-- Returned Items Table -->
        <table class="w-full border-collapse mb-4">
            <thead>
                <tr>
                    <th class="bg-blue-900 text-white rounded-l-lg text-left p-3 font-semibold text-sm w-[30%]">Item
                        Description</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[15%]">Rented Qty</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[20%]">Returned Qty</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[15%]">Price (Ks)</th>
                    <th class="bg-blue-900 text-white rounded-r-lg text-right p-3 font-semibold text-sm w-[20%]">Daily Total (Ks)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($return->items as $item)
                    <tr>
                        <td class="p-2 border-b border-gray-200 text-sm">
                            <p class="font-semibold text-gray-800">
                                {{ $item->rentItem->productVariant->product->product_name ?? 'N/A' }}
                                @if ($item->rentItem->productVariant->size ?? false)
                                    - Size: {{ $item->rentItem->productVariant->size }}
                                @endif
                            </p>
                            {{-- <p class="text-gray-600 text-xs">
                                {{ $item->rentItem->productVariant->product->description ?? '' }}</p> --}}
                        </td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">
                            {{ $item->rentItem->rent_qty ?? 0 }} {{ $item->rentItem->unit ?? '' }}
                        </td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">{{ $item->qty }}
                            {{ $item->rentItem->unit ?? '' }}</td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">
                            {{ number_format($item->rentItem->unit_price ?? 0, 0) }}
                        </td>
                        <td class="p-2 border-b border-gray-200 text-sm text-right">
                            {{ number_format($item->rentItem->total ?? 0, 0) }}
                        </td>
                    </tr>
                @endforeach

                @if ($return->items->count() > 0)
                    <tr class="bg-gray-50">
                        <td class="p-2 font-semibold text-sm" colspan="4">Daily Rental Subtotal</td>
                        <td class="p-2 text-sm text-right font-semibold">
                            {{ number_format($rent->sub_total ?? 0, 0) }} Ks
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="7" class="p-2 border-b border-gray-200 text-sm text-center text-gray-500">
                            No returned items found
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="grid grid-cols-2 gap-8 mb-4">
            <!-- Return Details & Conditions -->
            <div>
                <!-- Signatures -->
                <div class="flex justify-between mt-4 pt-4">
                    <div class="text-center w-[45%]">
                        <p class="text-gray-800 font-semibold text-sm mb-4">Customer's Signature</p>
                        <p class="text-gray-600 text-sm pt-2">_________________</p>
                    </div>
                    <div class="text-center w-[45%]">
                        <p class="text-gray-800 font-semibold text-sm mb-4">Authorized Signature</p>
                        <p class="text-gray-600 text-sm pt-2">_________________</p>
                    </div>
                </div>

                <!-- Terms & Conditions with Payment History -->
                <div class="bg-gray-100 p-4 rounded custom mt-6">
                    @if ($return->note)
                        <div class="mb-4 pb-3 border-b border-gray-300">
                            <h3 class="text-blue-700 font-bold mb-3">Return Policy & Payment Conditions</h3>

                            <p class="text-gray-600 text-sm mb-2">
                                {{ $return->note }}
                            </p>
                        </div>
                    @endif

                    <!-- Payment History -->
                    @if ($rent->payments->count() > 0)
                        <div>
                            <h4 class="text-blue-700 font-semibold mb-2">Payment History:</h4>
                            @foreach ($rent->payments as $payment)
                                <p class="text-gray-600 text-sm mb-1">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}:
                                    {{ number_format($payment->amount, 0) }} Ks via
                                    <span class="font-medium">{{ ucfirst($payment->payment_method) }}</span>
                                    @if ($payment->payment_for)
                                        ({{ $payment->payment_for }})
                                    @endif
                                </p>
                            @endforeach

                        </div>
                    @endif

                </div>
            </div>

            <!-- Financial Summary -->
            <div class="flex justify-end mb-8">
                <table class="w-full max-w-sm border-collapse">
                    <!-- Rental Period Calculation -->
                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Total Days</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">
                            {{ $return->total_days }} days
                        </td>
                    </tr>

                    <!-- Total Rental Amount -->
                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm font-semibold">Total Rental Amount</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right font-semibold">
                            {{ number_format($return->total_rental_amount, 0) }} Ks
                        </td>
                    </tr>

                    <!-- Additions -->
                    @if (($return->transport ?? 0) > 0)
                        <tr>
                            <td class="p-1 border-b border-gray-200 text-sm">Transport</td>
                            <td class="p-1 border-b border-gray-200 text-sm text-right">
                                + {{ number_format($return->transport, 0) }} Ks
                            </td>
                        </tr>
                    @endif

                    @if ($return->total_damage_fee > 0)
                        <tr>
                            <td class="p-1 border-b border-gray-200 text-sm">Damage/Loss Fees</td>
                            <td class="p-1 border-b border-gray-200 text-sm text-right text-red-600">
                                + {{ number_format($return->total_damage_fee, 0) }} Ks
                            </td>
                        </tr>
                    @endif

                    {{-- @if ($return->collect_amount > 0)
                        <tr>
                            <td class="border-b border-gray-200 text-sm">Additional Charges</td>
                            <td class="border-b border-gray-200 text-sm text-right text-red-600">
                                + {{ number_format($return->collect_amount, 0) }} Ks
                            </td>
                        </tr>
                    @endif --}}

                    <!-- Deductions -->
                    @if (($rent->deposit ?? 0) > 0)
                        <tr>
                            <td class="p-1 border-b border-gray-200 text-sm">Deposit</td>
                            <td class="p-1 border-b border-gray-200 text-sm text-right text-blue-600">
                                - {{ number_format($rent->deposit, 0) }} Ks
                            </td>
                        </tr>
                    @endif

                    <!-- Payment Total -->
                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm font-semibold">Payment Total</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right font-semibold">
                            - {{ number_format($totalPaymentByRentId, 0) }} Ks
                        </td>
                    </tr>

                    <!-- Final Balance -->
                    <tr class="font-bold bg-gray-100">
                        <td class="p-1 border-t-1 border-b-1 border-blue-700">
                            FINAL BALANCE
                        </td>
                        <td
                            class="p-1 border-t-1 border-b-1 border-blue-700 text-right {{ $return->refund_amount > 0 ? 'text-red-600' : ($return->collect_amount > 0 ? 'text-blue-600' : 'text-green-600') }}">
                            @if ($return->refund_amount > 0)
                                {{ number_format($return->refund_amount, 0) }} Ks (Refund)
                            @elseif($return->collect_amount > 0)
                                {{ number_format(abs($return->collect_amount), 0) }} Ks (Settled with Customer)
                            @else
                                0 Ks (Settled)
                            @endif
                        </td>
                    </tr>

                    <!-- Status Summary -->
                    <tr>
                        <td class="text-xs text-gray-500" colspan="2">
                            @if ($return->collect_amount > 0)
                                <div class="flex items-center text-red-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Balance Settled with customer
                                </div>
                            @elseif($return->refund_amount > 0)
                                <div class="flex items-center text-blue-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Refund to be processed to customer
                                </div>
                            @else
                                <div class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Account fully settled
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center pt-4 mt-4 border-t border-gray-200">
            <h3 class="text-blue-700 font-bold mb-1">Thank you for choosing Kyaw Family Scaffolding!</h3>
            {{-- <p class="text-gray-600 text-sm mb-4">For any inquiries regarding this return receipt, please contact our
                returns department.</p> --}}
            <div class="text-gray-500 text-xs">
                <p>Phone: +95 9 428 111 750 | Email: sales@kyawscaffolding.com | Website: www.kyawscaffolding.com</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-6 print:hidden">
            <div>
                <a href="{{ route('rents.returns.show', [$rent->id, $return->id]) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700
                    hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    ← Back to Return Details
                </a>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('rents.print', $rent->id) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-blue-700 bg-white px-4 py-2 text-sm font-semibold text-blue-700
                    hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    📄 View Rental Invoice
                </a>
                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white
                    hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    🖨 Print Return Receipt
                </button>
            </div>
        </div>
    </div>

    {{-- <script>
        // Auto-print option (optional)
        @if (request('autoprint'))
            window.onload = function() {
                window.print();
            };
        @endif
    </script> --}}
@endsection
