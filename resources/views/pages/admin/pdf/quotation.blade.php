<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $quotationData['quotation_title'] }} - {{ $quotationData['quotation_no'] }}</title>
    <style>
        @page {
            margin: 20mm;
            size: A4;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1e40af;
        }
        
        .company-info h1 {
            color: #1e40af;
            font-size: 24px;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        
        .quotation-title {
            color: #1e40af;
            font-size: 28px;
            font-weight: bold;
            text-align: right;
            margin: 0 0 10px 0;
        }
        
        .client-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        th {
            background: #1e40af;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
        }
        
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        
        .totals-table {
            width: 300px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        
        .totals-table td {
            border-bottom: 1px solid #e5e7eb;
            padding: 8px 4px;
        }
        
        .grand-total {
            font-weight: bold;
            font-size: 16px;
            color: #1e40af;
            border-top: 2px solid #1e40af;
            border-bottom: 2px solid #1e40af;
        }
        
        .terms {
            margin-top: 40px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
            font-size: 11px;
            line-height: 1.6;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-bold {
            font-weight: bold;
        }
        
        .mb-2 {
            margin-bottom: 8px;
        }
        
        .mb-4 {
            margin-bottom: 16px;
        }
        
        .mb-6 {
            margin-bottom: 24px;
        }
        
        .w-50 {
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $quotationData['company_email'] }}</h1>
                <p style="font-size: 11px; color: #6b7280; margin: 0;">
                    Address:
                    Phone: 0912345678
                </p>
            </div>
            <div style="text-align: right;">
                <h2 class="quotation-title">{{ $quotationData['quotation_title'] }}</h2>
                <div style="font-size: 12px; color: #6b7280;">
                    <p style="margin: 2px 0;"><strong>Quotation No:</strong> {{ $quotationData['quotation_no'] }}</p>
                    <p style="margin: 2px 0;"><strong>Date:</strong> {{ $quotationData['formatted_date'] }}</p>
                </div>
            </div>
        </div>

        <!-- Client Info -->
        <div class="client-info">
            <div style="width: 48%;">
                <h3 style="color: #1e40af; font-size: 14px; margin: 0 0 10px 0; font-weight: bold;">QUOTATION TO:</h3>
                <p style="margin: 4px 0; font-size: 13px;"><strong>{{ $quotationData['client_name'] }}</strong></p>
                {{-- @if($quotationData['client_address'])
                    <p style="margin: 4px 0; font-size: 12px;">{{ $quotationData['client_address'] }}</p>
                @endif
                @if($quotationData['client_phone'])
                    <p style="margin: 4px 0; font-size: 12px;">Phone: {{ $quotationData['client_phone'] }}</p>
                @endif --}}
                @if($quotationData['client_email'])
                    <p style="margin: 4px 0; font-size: 12px;">Email: {{ $quotationData['client_email'] }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Description</th>
                    <th style="width: 15%; text-align: center;">Quantity</th>
                    <th style="width: 10%; text-align: center;">Unit</th>
                    <th style="width: 15%; text-align: right;">Unit Price</th>
                    <th style="width: 20%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotationData['items'] as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td style="text-align: center;">{{ number_format($item['quantity'], 2) }}</td>
                    <td style="text-align: center;">{{ $item['unit'] }}</td>
                    <td style="text-align: right;">${{ number_format($item['unit_price'], 2) }}</td>
                    <td style="text-align: right; font-weight: bold;">${{ number_format($item['line_total'], 2) }}</td>
                </tr>
                @endforeach
                
                <!-- Subtotal Row -->
                <tr>
                    <td colspan="4" style="text-align: right; padding-top: 20px; font-weight: bold;">Items Subtotal</td>
                    <td style="text-align: right; padding-top: 20px; font-weight: bold;">${{ number_format($quotationData['items_subtotal'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            @if($quotationData['secure_deposit'] > 0)
            <tr>
                <td>Secure Deposit:</td>
                <td style="text-align: right;">+ ${{ number_format($quotationData['secure_deposit'], 2) }}</td>
            </tr>
            @endif
            
            @if($quotationData['transport_fee'] > 0)
            <tr>
                <td>Transport Fee:</td>
                <td style="text-align: right;">+ ${{ number_format($quotationData['transport_fee'], 2) }}</td>
            </tr>
            @endif
            
            @if($quotationData['discount'] > 0)
            <tr style="color: #10b981;">
                <td>Discount:</td>
                <td style="text-align: right;">- ${{ number_format($quotationData['discount'], 2) }}</td>
            </tr>
            @endif
            
            @if($quotationData['tax_percentage'] > 0)
            <tr>
                <td>Tax ({{ $quotationData['tax_percentage'] }}%):</td>
                <td style="text-align: right;">+ ${{ number_format($quotationData['tax_amount'], 2) }}</td>
            </tr>
            @endif
            
            <tr class="grand-total">
                <td>GRAND TOTAL:</td>
                <td style="text-align: right;">${{ number_format($quotationData['grand_total'], 2) }}</td>
            </tr>
        </table>

        <!-- Terms & Conditions -->
        @if($quotationData['terms'])
        <div class="terms">
            <h3 style="color: #1e40af; font-size: 13px; margin: 0 0 10px 0; font-weight: bold;">TERMS & CONDITIONS:</h3>
            <div style="white-space: pre-line;">{{ $quotationData['terms'] }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer generated quotation. No signature required.</p>
            <p>For any inquiries, please contact: {{ $quotationData['company_email'] }}</p>
            <p>Quotation No: {{ $quotationData['quotation_no'] }} | Date: {{ $quotationData['formatted_date'] }}</p>
        </div>
    </div>
</body>
</html>