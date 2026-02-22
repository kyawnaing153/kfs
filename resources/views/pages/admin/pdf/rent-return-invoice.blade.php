<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Return Receipt #{{ $return->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
            color: #333;
        }
        .invoice-container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1e40af;
        }
        .company-info h1 {
            color: #1e40af;
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        .company-info p {
            color: #4b5563;
            font-size: 11px;
            margin: 2px 0;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-details h2 {
            color: #1e40af;
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        .invoice-details p {
            color: #4b5563;
            font-size: 11px;
            margin: 2px 0;
        }
        .font-semibold {
            font-weight: 600;
        }
        .text-gray-800 {
            color: #1f2937;
        }
        .text-gray-600 {
            color: #4b5563;
        }
        .text-blue-700 {
            color: #1e40af;
        }
        .text-red-600 {
            color: #dc2626;
        }
        .text-blue-600 {
            color: #2563eb;
        }
        .text-green-600 {
            color: #16a34a;
        }
        .mb-4 {
            margin-bottom: 15px;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
        .mt-4 {
            margin-top: 15px;
        }
        .mt-6 {
            margin-top: 20px;
        }
        .pt-4 {
            padding-top: 15px;
        }
        .pb-6 {
            padding-bottom: 20px;
        }
        .border-b {
            border-bottom: 1px solid #e5e7eb;
        }
        .border-t {
            border-top: 1px solid #e5e7eb;
        }
        .border-blue-700 {
            border-color: #1e40af;
        }
        .grid {
            display: table;
            width: 100%;
        }
        .grid-cols-2 {
            display: table;
            width: 100%;
        }
        .grid-cols-2 > div {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .gap-8 {
            padding-right: 20px;
        }
        .w-\[48\%\] {
            width: 48%;
        }
        .w-\[45\%\] {
            width: 45%;
        }
        .flex {
            display: flex;
        }
        .justify-between {
            justify-content: space-between;
        }
        .items-center {
            align-items: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-sm {
            font-size: 11px;
        }
        .text-xs {
            font-size: 10px;
        }
        .font-bold {
            font-weight: 700;
        }
        .uppercase {
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th {
            background-color: #1e3a8a;
            color: white;
            text-align: left;
            padding: 8px;
            font-size: 11px;
            font-weight: 600;
        }
        th:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        th:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        .bg-gray-50 {
            background-color: #f9fafb;
        }
        .bg-gray-100 {
            background-color: #f3f4f6;
        }
        .p-1 {
            padding: 4px;
        }
        .p-2 {
            padding: 6px;
        }
        .p-3 {
            padding: 10px;
        }
        .p-4 {
            padding: 12px;
        }
        .p-10 {
            padding: 20px;
        }
        .rounded {
            border-radius: 8px;
        }
        .rounded-l-lg {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        .rounded-r-lg {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        .max-w-sm {
            max-width: 350px;
        }
        .ml-\[60\%\] {
            margin-left: 60%;
        }
        .border-gray-200 {
            border-color: #e5e7eb;
        }
        .border-gray-300 {
            border-color: #d1d5db;
        }
        .border-t-1 {
            border-top-width: 1px;
        }
        .border-b-1 {
            border-bottom-width: 1px;
        }
        .border-blue-700 {
            border-color: #1e40af;
        }
        .footer {
            text-align: center;
            padding-top: 15px;
            margin-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #6b7280;
        }
        .footer h3 {
            color: #1e40af;
            font-weight: bold;
            margin: 0 0 5px 0;
            font-size: 12px;
        }
        .page-break {
            page-break-after: always;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 150px;
            margin-top: 20px;
        }
        .custom {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-container p-10">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>Kyaw Family Scaffolding</h1>
                <p>123 Construction Street, Yangon, Myanmar</p>
                <p>Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com</p>
            </div>
            <div class="invoice-details">
                <h2>RETURN RECEIPT</h2>
                <p><span class="font-semibold">Receipt No:</span> RETURN-{{ $return->id }}</p>
                <p><span class="font-semibold">Rent No:</span> {{ $rent->rent_code }}</p>
                <p><span class="font-semibold">Print Date:</span> {{ $return->current_time ?? now()->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <!-- Client Section -->
        <div class="flex justify-between mb-4">
            <div class="w-[48%]">
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
            <div class="w-[48%] ml-[60%]">
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
        <table>
            <thead>
                <tr>
                    <th class="w-[30%]">Item Description</th>
                    <th class="text-center w-[15%]">Rented Qty</th>
                    <th class="text-center w-[20%]">Returned Qty</th>
                    <th class="text-center w-[15%]">Price (Ks)</th>
                    <th class="text-right w-[20%]">Daily Total (Ks)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($return->items as $item)
                    <tr>
                        <td>
                            <p class="font-semibold">
                                {{ $item->rentItem->productVariant->product->product_name ?? 'N/A' }}
                                @if ($item->rentItem->productVariant->size ?? false)
                                    - Size: {{ $item->rentItem->productVariant->size }}
                                @endif
                            </p>
                        </td>
                        <td class="text-center">
                            {{ $item->rentItem->rent_qty ?? 0 }} {{ $item->rentItem->unit ?? '' }}
                        </td>
                        <td class="text-center">{{ $item->qty }} {{ $item->rentItem->unit ?? '' }}</td>
                        <td class="text-center">{{ number_format($item->rentItem->unit_price ?? 0, 0) }}</td>
                        <td class="text-right">{{ number_format($item->rentItem->total ?? 0, 0) }}</td>
                    </tr>
                @endforeach

                @if ($return->items->count() > 0)
                    <tr class="bg-gray-50">
                        <td class="font-semibold" colspan="4">Daily Rental Subtotal</td>
                        <td class="text-right font-semibold">
                            {{ number_format($rent->sub_total ?? 0, 0) }} Ks
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5" class="text-center text-gray-500">
                            No returned items found
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="grid-cols-2">
            <div class="gap-8">
                <!-- Signatures -->
                <div class="flex justify-between mt-4 pt-4">
                    <div class="text-center w-[45%]">
                        <p class="font-semibold text-sm mb-4">Customer's Signature</p>
                        <p class="text-sm pt-2">_________________</p>
                    </div>
                    <div class="text-center w-[45%]">
                        <p class="font-semibold text-sm mb-4">Authorized Signature</p>
                        <p class="text-sm pt-2">_________________</p>
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
            <div class="text-right">
                <table class="max-w-sm" style="float: right;">
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
                        <td class="p-1 border-t-1 border-b-1 border-blue-700 text-right {{ $return->refund_amount > 0 ? 'text-red-600' : ($return->collect_amount > 0 ? 'text-blue-600' : 'text-green-600') }}">
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
                                <div>⚠️ Balance Settled with customer</div>
                            @elseif($return->refund_amount > 0)
                                <div>↩️ Refund to be processed to customer</div>
                            @else
                                <div>✓ Account fully settled</div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h3>Thank you for choosing Kyaw Family Scaffolding!</h3>
            <p>Phone: +95 9 428 111 750 | Email: sales@kyawscaffolding.com | Website: www.kyawscaffolding.com</p>
        </div>
    </div>
</body>
</html>