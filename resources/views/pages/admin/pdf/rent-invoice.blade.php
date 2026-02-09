<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $rent->rent_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-info { margin-bottom: 20px; }
        .customer-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totals { float: right; width: 300px; }
        .totals table { width: 100%; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>INVOICE</h1>
            <h2>#{{ $invoice_number }}</h2>
        </div>

        <div class="company-info">
            <strong>{{ $company['name'] }}</strong><br>
            {{ $company['address'] }}<br>
            Phone: {{ $company['phone'] }}<br>
            Email: {{ $company['email'] }}
        </div>

        <div class="customer-info">
            <strong>Bill To:</strong><br>
            {{ $rent->customer->name }}<br>
            {{ $rent->customer->email ?? 'N/A' }}<br>
            {{ $rent->customer->phone_number ?? 'N/A' }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Size/Variant</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rent->items as $item)
                <tr>
                    <td>{{ $item->productVariant->product->name }}</td>
                    <td>{{ $item->productVariant->size }}</td>
                    <td>{{ $item->rent_qty }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td><strong>Sub Total:</strong></td>
                    <td>${{ number_format($rent->sub_total, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Transport:</strong></td>
                    <td>${{ number_format($rent->transport, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Deposit:</strong></td>
                    <td>${{ number_format($rent->deposit, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Discount:</strong></td>
                    <td>${{ number_format($rent->discount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td><strong>${{ number_format($rent->total, 2) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Paid:</strong></td>
                    <td>${{ number_format($rent->total_paid, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Due:</strong></td>
                    <td><strong>${{ number_format($rent->total_due, 2) }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Invoice generated on {{ date('F d, Y H:i:s') }}</p>
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>