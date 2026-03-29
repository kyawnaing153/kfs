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
                <h1 class="text-blue-900 text-2xl font-bold mb-1">Kyaw Family Scaffolding</h1>
                <p class="text-gray-600 text-sm">123 Construction Street, Yangon, Myanmar</p>
                <p class="text-gray-600 text-sm">Phone: +95 1 234 5678 | Email: {{ $invoiceData['company_email'] }}</p>
            </div>
            <div class="invoice-details text-right">
                <h2 class="text-blue-900 text-2xl font-bold mb-1">RENTAL INVOICE</h2>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Invoice No:</span>
                    {{ $invoiceData['invoice_no'] }}</p>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Print Date:</span>
                    {{ $invoiceData['current_time'] }}</p>
                <p class="text-gray-600 text-sm mb-1"><span class="font-semibold text-gray-800">Rent Date:</span>
                    {{ $invoiceData['date'] }}</p>
            </div>
        </div>

        <!-- Client Section -->
        <div class="flex justify-between items-center mb-4">
            <div class="bill-to w-[48%]">
                <div class="text-blue-900 font-bold text-sm uppercase mb-2">Rented To:</div>
                <div class="text-gray-600 text-sm">
                    <p class="font-semibold text-gray-800">
                        {{ $invoiceData['client_name'] }}
                    </p>
                    <p>Phone: {{ $invoiceData['client_phone'] }}</p>
                    <p>Email: {{ $invoiceData['client_email'] }}</p>
                </div>
            </div>
            <div class="ship-to w-[48%] lg:ml-[50%]">
                <div class="text-blue-900 font-bold text-sm uppercase mb-2">Delivery To:</div>
                <div class="text-gray-600 text-sm">
                    <p class="font-semibold text-gray-800">Project Site</p>
                    <p>{{ $invoiceData['client_address'] }}</p>
                    <p>Phone: {{ $invoiceData['client_phone'] }}</p>
                </div>
            </div>
        </div>

        <!-- Rental Items Table -->
        <table class="w-full border-collapse mb-4">
            <tbody>
                <tr>
                    <th class="bg-blue-900 text-white rounded-l-lg text-left p-3 font-semibold text-sm w-[40%]">Item
                        Description</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[15%]">Quantity</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[5%]">Unit</th>
                    <th class="bg-blue-900 text-white text-center p-3 font-semibold text-sm w-[20%]">Daily Rate (Ks)</th>
                    <th class="bg-blue-900 text-white rounded-r-lg text-right p-3 font-semibold text-sm w-[20%]">Daily Total
                        (Ks)</th>
                </tr>
            </tbody>
            <tbody>

                @foreach ($invoiceData['items'] as $item)
                    <tr>
                        <td class="p-2 border-b border-gray-200 text-sm">
                            <p class="font-semibold text-gray-800">
                                {{ $item['name'] }}
                            </p>
                        </td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">{{ $item['quantity'] }}</td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">{{ $item['unit'] }}</td>
                        <td class="p-2 border-b border-gray-200 text-sm text-center">
                            {{ $item['unit_price'] }}
                        </td>
                        <td class="p-2 border-b border-gray-200 text-sm text-right">
                            {{ $item['line_total'] }}
                        </td>
                    </tr>
                @endforeach

                <tr class="bg-gray-50">
                    <td class="p-2 font-semibold text-sm" colspan="4">Daily Rental Subtotal</td>
                    <td class="p-2 text-sm text-right font-semibold">{{ $invoiceData['items_subtotal'] }} Ks
                    </td>
                </tr>
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
                        Customer is responsible for any damage or loss of rented items
                    </p>

                    <p class="text-gray-600 text-sm"><span class="font-semibold text-gray-800">K-pay Number:</span>
                        09428111750
                    </p>

                    <!-- Payment History -->
                </div>
            </div>


            <!-- Summary Section -->
            <div class="flex justify-end mb-8">
                <table class="w-full max-w-sm border-collapse">
                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Security Deposit</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">+
                            {{ $invoiceData['secure_deposit'] ?? 0 }} Ks</td>
                    </tr>
                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Transport Fee</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">+
                            {{ $invoiceData['transport_fee'] ?? 0 }} Ks</td>
                    </tr>
                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Discount</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">-
                            {{ $invoiceData['discount'] ?? 0 }} Ks</td>
                    </tr>
                    {{-- <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Subtotal</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">
                            {{ $invoiceData['items_subtotal'] }} Ks</td>
                    </tr> --}}

                    <tr>
                        <td class="p-1 border-b border-gray-200 text-sm">Tax (1%)</td>
                        <td class="p-1 border-b border-gray-200 text-sm text-right">
                            {{ $invoiceData['tax_amount'] }} Ks</td>
                    </tr>

                    <tr class="font-bold bg-gray-100">
                        <td class="p-1 border-t-1 border-b-1 border-blue-900">
                            TOTAL
                        </td>
                        <td class="p-1 border-t-1 border-b-1 border-blue-900 text-right">
                            {{ $invoiceData['grand_total'] }} Ks
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center pt-4 mt-4 border-t border-gray-200">
            <h3 class="text-blue-900 font-bold mb-1">Thank you for choosing Kyaw Family Scaffolding!</h3>
            <p class="text-gray-600 text-sm mb-4">For any inquiries regarding this rental invoice, please contact us.</p>
            <div class="text-gray-500 text-xs">
                <p>Phone: +95 1 234 5678 | Email: sale@kyawfamilyscaffolding.com | Website: www.kyawscaffolding.com</p>
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
