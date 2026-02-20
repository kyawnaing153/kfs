<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sale Invoice - SALE-202602-01</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
        }
        
        /* Header Section */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1e3a8a;
        }
        
        .header-left, .header-right {
            display: table-cell;
            vertical-align: top;
        }
        
        .header-left {
            width: 60%;
        }
        
        .header-right {
            width: 40%;
            text-align: right;
        }
        
        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 8px;
        }
        
        .company-info {
            color: #666;
            font-size: 11px;
            line-height: 1.6;
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .invoice-meta {
            font-size: 11px;
            line-height: 1.8;
        }
        
        .invoice-meta strong {
            color: #333;
        }
        
        /* Customer Info Section */
        .customer-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .customer-left, .customer-right {
            display: table-cell;
            vertical-align: top;
            width: 50%;
        }
        
        .section-label {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .customer-details {
            font-size: 11px;
            line-height: 1.6;
            color: #555;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        
        .items-table thead th {
            background-color: #1e3a8a;
            color: #fff;
            padding: 12px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        
        .items-table thead th:last-child,
        .items-table tbody td:last-child {
            text-align: right;
        }
        
        .items-table thead th:nth-child(3),
        .items-table tbody td:nth-child(3) {
            text-align: center;
        }
        
        .items-table thead th:nth-child(4),
        .items-table tbody td:nth-child(4) {
            text-align: right;
        }
        
        .items-table tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e5e5;
            font-size: 11px;
        }
        
        /* Bottom Section */
        .bottom-section {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        
        .bottom-left {
            display: table-cell;
            width: 55%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .bottom-right {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }
        
        /* Signatures */
        .signatures {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }
        
        .signature-label {
            font-size: 11px;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
        }
        
        .signature-line {
            border-top: 1px solid #999;
            width: 80%;
            margin: 0 auto;
            padding-top: 5px;
        }
        
        /* Terms Box */
        .terms-box {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 4px;
        }
        
        .terms-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        
        .terms-content {
            font-size: 10px;
            line-height: 1.6;
            color: #555;
        }
        
        .terms-content strong {
            color: #333;
        }
        
        /* Totals Table */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 6px 0;
            font-size: 11px;
        }
        
        .totals-table td:first-child {
            text-align: left;
            color: #555;
        }
        
        .totals-table td:last-child {
            text-align: right;
            color: #333;
        }
        
        .totals-table .grand-total {
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 8px 0;
        }
        
        .totals-table .paid-status {
            color: #16a34a;
            font-weight: bold;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
        }
        
        .footer-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 5px;
        }
        
        .footer-text {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .footer-contact {
            font-size: 10px;
            color: #888;
        }
        
        .print-btn {
            display: inline-block;
            background-color: #1e3a8a;
            color: #fff;
            padding: 8px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 11px;
            margin-top: 15px;
        }
        
        /* Utility */
        .text-right {
            text-align: right;
        }
        
        .text-left {
            text-align: left;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
<base target="_blank">
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <div class="company-name">Kyaw Family Scaffolding</div>
                <div class="company-info">
                    123 Construction Street, Yangon, Myanmar<br>
                    Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com
                </div>
            </div>
            <div class="header-right">
                <div class="invoice-title">SALE INVOICE</div>
                <div class="invoice-meta">
                    <strong>Invoice No:</strong> SALE-202602-01<br>
                    <strong>Print Date:</strong> 2026-02-16 14:48<br>
                    <strong>Sale Date:</strong> 2026-02-15<br>
                    <strong>Payment Method:</strong> Kpay
                </div>
            </div>
        </div>
        
        <!-- Customer Info -->
        <div class="customer-section">
            <div class="customer-left">
                <div class="section-label">RENTED TO:</div>
                <div class="customer-details">
                    GIC<br>
                    Phone: 0912345678<br>
                    Email: labroom108@gmail.com
                </div>
            </div>
            <div class="customer-right">
                <div class="section-label">DELIVERY TO:</div>
                <div class="customer-details">
                    Project Site<br>
                    California,<br>
                    Phone: 0912345678
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Daily Rate (Ks)</th>
                    <th>Daily Total (Ks)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Scaffold - Size: 6 ft</td>
                    <td>10</td>
                    <td>set</td>
                    <td>95,000</td>
                    <td>950,000</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Bottom Section -->
        <div class="bottom-section">
            <div class="bottom-left">
                <!-- Signatures -->
                <div class="signatures">
                    <div class="signature-box">
                        <div class="signature-label">Customer's Signature</div>
                        <div class="signature-line"></div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-label">Authorized Signature</div>
                        <div class="signature-line"></div>
                    </div>
                </div>
                
                <!-- Terms & Conditions -->
                <div class="terms-box">
                    <div class="terms-title">Sale Terms & Conditions</div>
                    <div class="terms-content">
                        <strong>Damage/Loss:</strong> Customer is responsible for any damage or loss of sold items<br>
                        <strong>K-pay Number:</strong> 09428111750 (Kyaw Thu)
                    </div>
                </div>
            </div>
            
            <div class="bottom-right">
                <!-- Totals -->
                <table class="totals-table">
                    <tr>
                        <td>Transport Fee</td>
                        <td>+ 5,000 Ks</td>
                    </tr>
                    <tr>
                        <td>Discount</td>
                        <td>- 5,000 Ks</td>
                    </tr>
                    <tr>
                        <td>Subtotal</td>
                        <td>950,000 Ks</td>
                    </tr>
                    <tr>
                        <td>Tax (0%)</td>
                        <td>0 Ks</td>
                    </tr>
                    <tr class="grand-total">
                        <td>GRAND TOTAL</td>
                        <td>950,000 Ks</td>
                    </tr>
                    <tr>
                        <td>Total Paid</td>
                        <td>950,000 Ks</td>
                    </tr>
                    <tr>
                        <td class="paid-status">PAID IN FULL</td>
                        <td class="paid-status">0 Ks</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-title">Thank you for choosing Kyaw Family Scaffolding!</div>
            <div class="footer-text">For any inquiries regarding this sale invoice, please contact us.</div>
            <div class="footer-contact">
                Phone: +95 1 234 5678 | Email: info@kyawscaffolding.com | Website: www.kyawscaffolding.com
            </div>
            <a href="#" class="print-btn" onclick="window.print(); return false;">Print Invoice</a>
        </div>
    </div>
</body>
</html>