<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $customSubject }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e40af; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9fafb; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
        .button { display: inline-block; background: #1e40af; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $quotationData['quotation_title'] }}</h1>
            <p>Quotation No: {{ $quotationData['quotation_no'] }}</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $quotationData['client_name'] }},</p>
            
            @if($customMessage)
                <p>{{ $customMessage }}</p>
            @else
                <p>Please find attached your quotation for review.</p>
            @endif
            
            <p><strong>Quotation Details:</strong></p>
            <ul>
                <li>Quotation No: {{ $quotationData['quotation_no'] }}</li>
                <li>Date: {{ $quotationData['formatted_date'] }}</li>
                <li>Items: {{ count($quotationData['items']) }} item(s)</li>
                <li>Total Amount: ${{ number_format($quotationData['grand_total'], 2) }}</li>
            </ul>
            
            <p>You can view the detailed quotation in the attached PDF document.</p>
            
            <p>Best regards,<br>
            {{ $quotationData['company_email'] }}</p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>If you have any questions, please contact us at {{ $quotationData['company_email'] }}</p>
        </div>
    </div>
</body>
</html>