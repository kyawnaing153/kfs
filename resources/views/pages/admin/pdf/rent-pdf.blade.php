<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice - {{ $rent->rent_code ?? 'INV-001' }}</title>
    <style>
        @font-face {
            font-family: 'Outfit';
            font-style: normal;
            font-weight: 400;
            /* dompdf prefers specific weights over ranges */
            font-display: swap;
            /* Note: If dompdf fails to load woff2, use a .ttf source */
            src: url(https://fonts.gstatic.com/s/outfit/v15/QGYvz_MVcBeNP4NJuktqQ4E.woff2) format('woff2');
            unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C0, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }

        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Page Setup for DomPDF */
        @page {
            size: A4;
            margin: 10mm 12mm;
        }

        body {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #4b5563;
            background: #ffffff;
        }

        /* Main Container */
        .invoice-container {
            width: 100%;
            max-width: 186mm;
            margin: 0 auto;
            padding: 5mm;
            background: #ffffff;
        }

        /* Table Reset */
        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            vertical-align: top;
        }

        /* Print Button */
        .print-button-container {
            text-align: right;
            margin-top: 15px;
        }

        .print-button {
            display: inline-block;
            padding: 8px 16px;
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
        }

        /* Hide print button when printing */
        @media print {
            .print-button-container {
                display: none;
            }

            body {
                background: white !important;
            }

            .invoice-container {
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-container">

        <!-- ==================== HEADER SECTION ==================== -->
        <table style="width: 100%; margin-bottom: 16px; border-bottom: 2px solid #1e3a8a; padding-bottom: 20px;">
            <tr>
                <!-- Company Info (Left) -->
                <td style="width: 58%; vertical-align: top;">
                    <h1 style="color: #1e3a8a; font-size: 20px; font-weight: bold; margin-bottom: 6px;">
                        Kyaw Family Scaffolding
                    </h1>
                    <p style="color: #4b5563; font-size: 11px; margin-bottom: 2px;">
                        123 Construction Street, Yangon, Myanmar
                    </p>
                    <p style="color: #4b5563; font-size: 11px;">
                        Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com
                    </p>
                </td>
                <!-- Invoice Details (Right) -->
                <td style="width: 42%; vertical-align: top; text-align: right;">
                    <h2 style="color: #1e3a8a; font-size: 18px; font-weight: bold; margin-bottom: 8px;">
                        RENTAL INVOICE
                    </h2>
                    <p style="color: #4b5563; font-size: 11px; margin-bottom: 3px;">
                        <span style="font-weight: 600; color: #1f2937;">Invoice No:</span>
                        {{ $rent->rent_code ?? 'N/A' }}
                    </p>
                    <p style="color: #4b5563; font-size: 11px; margin-bottom: 3px;">
                        <span style="font-weight: 600; color: #1f2937;">Print Date:</span>
                        {{ $rent->current_time ?? date('Y-m-d H:i') }}
                    </p>
                    <p style="color: #4b5563; font-size: 11px; margin-bottom: 3px;">
                        <span style="font-weight: 600; color: #1f2937;">Rent Date:</span>
                        {{ isset($rent->rent_date) ? \Carbon\Carbon::parse($rent->rent_date)->format('Y-m-d') : 'N/A' }}
                    </p>
                    <p style="color: #4b5563; font-size: 11px;">
                        <span style="font-weight: 600; color: #1f2937;">Payment Method:</span>
                        {{ isset($rent->payment_type) ? ucfirst($rent->payment_type) : 'N/A' }}
                    </p>
                </td>
            </tr>
        </table>

        <!-- ==================== CLIENT SECTION ==================== -->
        @php
            $phones = array_map('trim', explode(',', $rent->customer->phone_number ?? ''));
        @endphp
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <!-- Rented To (Left) -->
                <td style="width: 48%; vertical-align: top; padding-right: 10px;">
                    <p
                        style="color: #1e3a8a; font-weight: bold; font-size: 10px; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                        Rented To:
                    </p>
                    <p style="font-weight: 600; color: #1f2937; font-size: 11px; margin-bottom: 2px;">
                        {{ $rent->customer->name ?? '' }}
                    </p>
                    @if (!empty($rent->customer->company_name))
                        <p style="color: #4b5563; font-size: 11px; margin-bottom: 2px;">
                            {{ $rent->customer->company_name }}
                        </p>
                    @endif
                    <p style="color: #4b5563; font-size: 11px; margin-bottom: 2px;">
                        Phone: {{ $phones[0] ?? 'N/A' }}
                    </p>
                    @if (!empty($rent->customer->email))
                        <p style="color: #4b5563; font-size: 11px;">
                            Email: {{ $rent->customer->email }}
                        </p>
                    @endif
                </td>
                <!-- Delivery To (Right) -->
                <td style="width: 4%;"></td>
                <td style="width: 48%; vertical-align: top; padding-left: 10px;">
                    <p
                        style="color: #1e3a8a; font-weight: bold; font-size: 10px; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                        Delivery To:
                    </p>
                    <p style="font-weight: 600; color: #1f2937; font-size: 11px; margin-bottom: 2px;">
                        Project Site
                    </p>
                    <p style="color: #4b5563; font-size: 11px; margin-bottom: 2px;">
                        {{ $rent->note ?? '' }}
                    </p>
                    <p style="color: #4b5563; font-size: 11px;">
                        Phone: {{ $phones[1] ?? '' }}
                    </p>
                </td>
            </tr>
        </table>

        <!-- ==================== RENTAL ITEMS TABLE ==================== -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <!-- Table Header -->
            <thead>
                <tr>
                    <th
                        style="background-color: #1e3a8a; color: #ffffff; padding: 10px 8px; text-align: left; font-size: 11px; font-weight: 600; width: 40%; border-left-radius: 14px;">
                        Item Description
                    </th>
                    <th
                        style="background-color: #1e3a8a; color: #ffffff; padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 600; width: 15%;">
                        Quantity
                    </th>
                    <th
                        style="background-color: #1e3a8a; color: #ffffff; padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 600; width: 10%;">
                        Unit
                    </th>
                    <th
                        style="background-color: #1e3a8a; color: #ffffff; padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 600; width: 17%;">
                        Daily Rate (Ks)
                    </th>
                    <th
                        style="background-color: #1e3a8a; color: #ffffff; padding: 10px 8px; text-align: right; font-size: 11px; font-weight: 600; width: 18%;">
                        Daily Total (Ks)
                    </th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                @if (isset($rent->items) && count($rent->items) > 0)
                    @foreach ($rent->items as $item)
                        <tr>
                            <td
                                style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; vertical-align: top;">
                                <p style="font-weight: 600; color: #1f2937; margin-bottom: 2px;">
                                    {{ $item->productVariant->product->product_name ?? 'N/A' }}
                                    @if (!empty($item->productVariant->size))
                                        <span style="font-weight: 400; color: #4b5563;">- Size:
                                            {{ $item->productVariant->size }}</span>
                                    @endif
                                </p>
                            </td>
                            <td
                                style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: center;">
                                {{ $item->rent_qty ?? 0 }}
                            </td>
                            <td
                                style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: center;">
                                {{ $item->unit ?? 'pcs' }}
                            </td>
                            <td
                                style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: center;">
                                {{ number_format($item->unit_price ?? 0, 0) }}
                            </td>
                            <td
                                style="padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: right;">
                                {{ number_format($item->total ?? 0, 0) }}
                            </td>
                        </tr>
                    @endforeach
                    <!-- Daily Subtotal Row -->
                    <tr style="background-color: #f9fafb;">
                        <td colspan="4"
                            style="padding: 10px 8px; font-weight: 600; font-size: 11px; text-align: right;">
                            Daily Rental Subtotal
                        </td>
                        <td style="padding: 10px 8px; font-size: 11px; text-align: right; font-weight: 600;">
                            {{ number_format($rent->sub_total ?? 0, 0) }} Ks
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5"
                            style="padding: 20px 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: center; color: #6b7280;">
                            No rental items found
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- ==================== BOTTOM SECTION: TWO COLUMNS ==================== -->
        <table style="width: 100%; margin-bottom: 15px;">
            <tr>
                <!-- LEFT COLUMN: Signatures & Terms -->
                <td style="width: 55%; vertical-align: top; padding-right: 15px;">

                    <!-- Signatures Table -->
                    <table style="width: 100%; margin-top: 15px;">
                        <tr>
                            <td style="width: 50%; text-align: center; padding: 10px;">
                                <p style="color: #1f2937; font-weight: 600; font-size: 11px; margin-bottom: 30px;">
                                    Customer's Signature
                                </p>
                                <p style="color: #4b5563; font-size: 11px;">
                                    ___________________
                                </p>
                            </td>
                            <td style="width: 50%; text-align: center; padding: 10px;">
                                <p style="color: #1f2937; font-weight: 600; font-size: 11px; margin-bottom: 30px;">
                                    Authorized Signature
                                </p>
                                <p style="color: #4b5563; font-size: 11px;">
                                    ___________________
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- Terms & Conditions Box -->
                    <div style="background-color: #f3f4f6; padding: 15px; margin-top: 20px; border-radius: 6px;">
                        <h3 style="color: #1e3a8a; font-weight: bold; margin-bottom: 10px; font-size: 13px;">
                            Rental Terms &amp; Conditions
                        </h3>
                        <p style="color: #4b5563; font-size: 10px; margin-bottom: 6px; line-height: 1.5;">
                            <span style="font-weight: 600; color: #1f2937;">Damage/Loss:</span>
                            Customer is responsible for any damage or loss of rented items
                        </p>
                        @if (isset($rent->payment_type) && $rent->payment_type == 'kpay')
                            <p style="color: #4b5563; font-size: 10px; line-height: 1.5;">
                                <span style="font-weight: 600; color: #1f2937;">K-pay Number:</span>
                                09428111750
                            </p>
                        @endif

                        <!-- Payment History -->
                        @if (isset($rent->payments) && $rent->payments->count() > 0)
                            <div style="margin-top: 12px; padding-top: 10px; border-top: 1px dashed #d1d5db;">
                                <h4 style="color: #1e3a8a; font-weight: 600; margin-bottom: 8px; font-size: 11px;">
                                    Payment History:
                                </h4>
                                @foreach ($rent->payments as $payment)
                                    <p style="color: #4b5563; font-size: 10px; margin-bottom: 3px;">
                                        {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}:
                                        {{ number_format($payment->amount, 0) }} Ks via {{ $payment->payment_method }}
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </td>

                <!-- RIGHT COLUMN: Summary Table -->
                <td style="width: 45%; vertical-align: top; padding-left: 15px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        @if (($rent->deposit ?? 0) > 0)
                            <tr>
                                <td
                                    style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; width: 50%;">
                                    Security Deposit
                                </td>
                                <td
                                    style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: right; width: 50%;">
                                    + {{ number_format($rent->deposit, 0) }} Ks
                                </td>
                            </tr>
                        @endif

                        @if (($rent->transport ?? 0) > 0)
                            <tr>
                                <td style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px;">
                                    Transport Fee
                                </td>
                                <td
                                    style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: right;">
                                    + {{ number_format($rent->transport, 0) }} Ks
                                </td>
                            </tr>
                        @endif

                        @if (($rent->discount ?? 0) > 0)
                            <tr>
                                <td style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px;">
                                    Discount
                                </td>
                                <td
                                    style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: right;">
                                    - {{ number_format($rent->discount, 0) }} Ks
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px;">
                                Subtotal
                            </td>
                            <td
                                style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: right;">
                                {{ number_format($rent->sub_total ?? 0, 0) }} Ks
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px;">
                                Tax ({{ $rent->tax_amount ?? 0 }}%)
                            </td>
                            <td
                                style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: right;">
                                {{ number_format($rent->tax_amount ?? 0, 0) }} Ks
                            </td>
                        </tr>

                        <!-- Grand Total Row -->
                        <tr style="background-color: #f3f4f6;">
                            <td
                                style="padding: 8px 10px; border-top: 2px solid #1e3a8a; border-bottom: 2px solid #1e3a8a; font-weight: bold; font-size: 12px; color: #1e3a8a;">
                                GRAND TOTAL
                            </td>
                            <td
                                style="padding: 8px 10px; border-top: 2px solid #1e3a8a; border-bottom: 2px solid #1e3a8a; text-align: right; font-weight: bold; font-size: 12px; color: #1e3a8a;">
                                {{ number_format($rent->total ?? 0, 0) }} Ks
                            </td>
                        </tr>

                        <!-- Payment Status -->
                        @if (($rent->total_paid ?? 0) > 0)
                            <tr>
                                <td
                                    style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; font-weight: 600;">
                                    Total Paid
                                </td>
                                <td
                                    style="padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; text-align: right; font-weight: 600;">
                                    {{ number_format($rent->total_paid, 0) }} Ks
                                </td>
                            </tr>
                            <tr>
                                @if (($rent->total_due ?? 0) > 0)
                                    <td style="padding: 6px 10px; font-weight: bold; font-size: 11px; color: #dc2626;">
                                        BALANCE DUE
                                    </td>
                                    <td
                                        style="padding: 6px 10px; text-align: right; font-weight: bold; font-size: 11px; color: #dc2626;">
                                        {{ number_format($rent->total_due, 0) }} Ks
                                    </td>
                                @else
                                    <td style="padding: 6px 10px; font-weight: bold; font-size: 11px; color: #16a34a;">
                                        PAID IN FULL
                                    </td>
                                    <td
                                        style="padding: 6px 10px; text-align: right; font-weight: bold; font-size: 11px; color: #16a34a;">
                                        {{ number_format(abs($rent->total_due ?? 0), 0) }} Ks
                                    </td>
                                @endif
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        <!-- ==================== FOOTER ==================== -->
        <div style="text-align: center; padding-top: 15px; margin-top: 15px; border-top: 1px solid #e5e7eb;">
            <h3 style="color: #1e3a8a; font-weight: bold; margin-bottom: 5px; font-size: 13px;">
                Thank you for choosing Kyaw Family Scaffolding!
            </h3>
            <p style="color: #4b5563; font-size: 10px; margin-bottom: 10px;">
                For any inquiries regarding this rental invoice, please contact us.
            </p>
            <p style="color: #6b7280; font-size: 9px;">
                Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com | Website: www.kyawscaffolding.com
            </p>
        </div>

        <!-- Print Button -->
        <div class="print-button-container">
            <button onclick="window.print()" class="print-button">
                🖨 Print Invoice
            </button>
        </div>

    </div>

</body>

</html>
