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
        <div class="flex justify-between items-center mb-4 pb-6 border-b-2 border-blue-900">
            <div class="company-info">
                {{-- <div class="w-20 h-20 bg-blue-900 rounded-full flex items-center justify-center mb-2 overflow-hidden">
                    <img src="" alt="Kyaw Family Scaffolding Logo" class="w-full h-full object-cover">
                </div> --}}
                <h1 class="text-blue-900 text-2xl font-bold mb-1">Kyaw Family Scaffolding</h1>
                <p class="text-gray-600 text-sm">123 Construction Street, Yangon, Myanmar</p>
                <p class="text-gray-600 text-sm">Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com</p>
            </div>
            <div class="invoice-details text-right">
                <h2 class="text-blue-900 text-2xl font-bold mb-1">RENTAL INVOICE</h2>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Invoice No:</span>
                    {{ $rent->rent_code }}</p>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Print Date:</span>
                    {{ $rent->current_time }}</p>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Rent Date:</span>
                    {{ \Carbon\Carbon::parse($rent->rent_date)->format('Y-m-d') }}</p>
                <p class="text-gray-600 text-sm"><span class="font-semibold text-gray-800">Payment Method:</span>
                    {{ ucfirst($rent->payment_type) }}</p>
            </div>
        </div>

        <!-- Client Section -->
        <div class="flex justify-between items-center mb-4">
            <div class="bill-to w-[48%]">
                <div class="text-blue-900 font-bold text-sm uppercase mb-2">Rented To:</div>
                <div class="text-gray-600 text-sm">
                    <p class="font-semibold text-gray-800">
                        {{ $rent->customer->first_name ?? '' }} {{ $rent->customer->last_name ?? '' }}
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
            <div class="ship-to w-[48%] lg:ml-[50%]">
                <div class="text-blue-900 font-bold text-sm uppercase mb-2">Delivery To:</div>
                <div class="text-gray-600 text-sm">
                    <p class="font-semibold text-gray-800">Project Site</p>
                    <p>{{ $rent->customer->address ?? '' }}</p>
                    <p>Phone: {{ $rent->customer->phone_number ?? '' }}</p>
                </div>
            </div>
        </div>

        <!-- Rental Items Table -->
        <table class="w-full border-collapse mb-4">
            <tbody>
                <tr>
                    <th class="bg-blue-900 text-white rounded-l-lg text-left p-3 font-semibold text-sm w-[40%]">Item Description</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[15%]">Quantity</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[5%]">Unit</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[20%]">Daily Rate (Ks)</th>
                    <th class="bg-blue-900 text-white rounded-r-lg text-right p-3 font-semibold text-sm w-[20%]">Daily Total (Ks)</th>
                </tr>
            </tbody>
            <tbody>
                @foreach ($rent->items as $item)
                    <tr>
                        <td class="p-2 border-b border-gray-200 text-sm">
                            <p class="font-semibold text-gray-800">
                                {{ $item->productVariant->product->product_name ?? 'N/A' }}
                                @if ($item->productVariant->size ?? false)
                                    - Size: {{ $item->productVariant->size }}
                                @endif
                            </p>
                            {{-- <p class="text-gray-600 text-xs">{{ $item->productVariant->product->description ?? '' }}</p> --}}
                        </td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">{{ $item->rent_qty }}</td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">{{ $item->unit }}</td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">
                            {{ number_format($item->unit_price, 0) }}</td>
                        <td class="p-2 border-b border-gray-200 text-sm text-right">
                            {{ number_format($item->daily_total ?? $item->rent_qty * $item->unit_price, 0) }}</td>
                    </tr>
                @endforeach

                @if (count($rent->items) > 0)
                    <tr class="bg-gray-50">
                        <td class="p-2 font-semibold text-sm" colspan="4">Daily Rental Subtotal</td>
                        <td class="p-2 text-sm text-right font-semibold">{{ number_format($rent->daily_subtotal, 0) }} Ks
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5" class="p-2 border-b border-gray-200 text-sm text-center text-gray-500">
                            No rental items found
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="grid grid-cols-2 gap-8 mb-4">
            <!-- Signatures -->
            <div>
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

                <!-- Terms & Conditions -->
                <div class="bg-gray-100 p-4 rounded custom mt-6">
                    <h3 class="text-blue-900 font-bold mb-3">Rental Terms & Conditions</h3>
                    
                    <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Damage/Loss:</span>
                        Customer is
                        responsible for any damage or loss of rented items</p>

                    @if ($rent->payment_type == 'kpay')
                        <p class="text-gray-600 text-sm"><span class="font-semibold text-gray-800">K-pay Number:</span>
                            09428111750
                        </p>
                    @endif

                    <!-- Payment History -->
                    @if ($rent->payments->count() > 0)
                        <div class="mt-4">
                            <h4 class="text-blue-900 font-semibold mb-2">Payment History:</h4>
                            @foreach ($rent->payments as $payment)
                                <p class="text-gray-600 text-sm mb-1">
                                    {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}:
                                    {{ number_format($payment->amount, 0) }} Ks via {{ $payment->payment_method }}
                                </p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>


            <!-- Summary Section -->
            <div class="flex justify-end mb-8">
                <table class="w-full max-w-sm border-collapse">
                    @if (($rent->deposit ?? 0) > 0)
                        <tr>
                            <td class="p-1 border-b border-gray-200 text-sm">Security Deposit</td>
                            <td class="p-1 border-b border-gray-200 text-sm text-right">+
                                {{ number_format($rent->deposit, 0) }} Ks</td>
                        </tr>
                    @endif

                    @if (($rent->transport ?? 0) > 0)
                        <tr>
                            <td class="p-1 border-b border-gray-200 text-sm">Transport Fee</td>
                            <td class="p-1 border-b border-gray-200 text-sm text-right">+
                                {{ number_format($rent->transport, 0) }} Ks</td>
                        </tr>
                    @endif

                    @if (($rent->discount ?? 0) > 0)
                        <tr>
                            <td class="p-1 border-b border-gray-200 text-sm">Discount</td>
                            <td class="p-1 border-b border-gray-200 text-sm text-right">-
                                {{ number_format($rent->discount, 0) }} Ks</td>
                        </tr>
                    @endif

                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Subtotal</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">
                            {{ number_format($rent->sub_total, 0) }} Ks</td>
                    </tr>

                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Tax ({{ $rent->tax_amount }}%)</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">
                            {{ number_format($rent->tax_amount, 0) }} Ks</td>
                    </tr>

                    <tr class="font-bold bg-gray-100">
                        <td class="p-1 border-t-1 border-b-1 border-blue-900">
                            GRAND TOTAL
                        </td>
                        <td class="p-1 border-t-1 border-b-1 border-blue-900 text-right">
                            {{ number_format($rent->total, 0) }} Ks
                        </td>
                    </tr>

                    <!-- Payment Status -->
                    @if ($rent->total_paid > 0)
                        <tr>
                            <td class="p-1 border-b border-gray-200 text-sm font-semibold">Total Paid</td>
                            <td class="p-1 border-b border-gray-200 text-sm text-right font-semibold">
                                {{ number_format($rent->total_paid, 0) }} Ks</td>
                        </tr>
                        <tr class="{{ $rent->total_due > 0 ? 'text-red-600' : 'text-green-600' }} font-bold">
                            <td class="p-1 border-b border-gray-200">
                                {{ $rent->total_due > 0 ? 'BALANCE DUE' : 'PAID IN FULL' }}
                            </td>
                            <td class="p-1 border-b border-gray-200 text-right">
                                @if ($rent->total_due > 0)
                                    {{ number_format($rent->total_due, 0) }} Ks
                                @else
                                    {{ number_format(abs($rent->total_due), 0) }} Ks
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center pt-4 mt-4 border-t border-gray-200">
            <h3 class="text-blue-900 font-bold mb-1">Thank you for choosing Kyaw Family Scaffolding!</h3>
            <p class="text-gray-600 text-sm mb-4">For any inquiries regarding this rental invoice, please contact us.</p>
            <div class="text-gray-500 text-xs">
                {{-- <p>Kyaw Family Scaffolding | 123 Construction Street, Yangon, Myanmar</p> --}}
                <p>Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com | Website: www.kyawscaffolding.com</p>
            </div>
        </div>

        <div class="flex justify-end mb-4 print:hidden">
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2 text-sm font-semibold text-white
               hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                🖨 Print Invoice
            </button>
        </div>

    </div>
@endsection
