<x-mail::message>

# Sale Invoice Created

Hello **{{ $customer->name }}**,

Your sale invoice has been generated successfully. Please review the details below.

---

### Invoice Summary
<x-mail::table>
| | |
|:---|---:|
| **Customer Name** | {{ $customer->name }} |
| **Customer Email** | {{ $customer->email }} |
| **Invoice Number** | {{ $sale->sale_code }} |
| **Invoice Date** | {{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y') }} |
| **Due Date** | {{ \Carbon\Carbon::parse($sale->sale_date)->addDays(7)->format('M d, Y') }} |
</x-mail::table>

---

### Items Sold
<x-mail::table>
| Product Details | Quantity | Unit Price | Total |
|-----------------|----------|------------|-------|
@foreach ($sale->items as $item)
| **{{ $item->productVariant->product->product_name }}**<br><small style="color: #6c757d;">Size: {{ $item->productVariant->size }}</small> | {{ $item->sale_qty }} | {{ number_format($item->unit_price, 2) }} Ks | {{ number_format($item->total, 2) }} Ks |
@endforeach
</x-mail::table>

---

### Payment Breakdown
<x-mail::table>
| | |
|:---|---:|
| **Subtotal** | {{ number_format($sale->sub_total, 2) }} Ks |
| **Transportation** | {{ number_format($sale->transport, 2) }} Ks |
@if($sale->discount > 0)
| **Discount** | <span style="color: #28a745;">-{{ number_format($sale->discount, 2) }}Ks</span> |
@endif
| **Grand Total** | **{{ number_format($sale->total, 2) }}Ks** |
</x-mail::table>

---

### Amount Summary
<x-mail::panel>
<div style="background-color: #f8f9fa; padding: 16px; border-radius: 8px;">
    <table style="width: 100%;">
        <tr>
            <td style="padding: 8px 0;"><strong>Total Amount:</strong>
            <td style="padding: 8px 0; text-align: right;">{{ number_format($sale->total, 2) }} Ks</td>
        </tr>
        </tr>
            <td style="padding: 8px 0;"><strong>Amount Paid:</strong>
            <td style="padding: 8px 0; text-align: right; color: #28a745;">{{ number_format($sale->total_paid, 2) }} Ks</td>
        </tr>
        <tr style="border-top: 2px solid #dee2e6;">
            <td style="padding: 12px 0 8px 0;"><strong style="font-size: 18px;">Amount Due:</strong>
            <td style="padding: 12px 0 8px 0; text-align: right;">
                @if($sale->total_due > 0)
                    <strong style="font-size: 18px; color: #dc3545;">{{ number_format($sale->total_due, 1) }} Ks</strong>
                @else
                    <strong style="font-size: 18px; color: #28a745;">0.00 Ks</strong>
                    <div style="font-size: 12px; color: #28a745;">(Fully Paid)</div>
                @endif
            </td>
        </tr>
    </table>
</div>
</x-mail::panel>

---

### Payment Instructions
@if($sale->total_due > 0)
<x-mail::panel>
Please make payment using the following methods:
- **Bank Transfer:** Account Name: {{ config('app.name') }}, Account Number: 1234567890
- **Credit Card:** Available through the online invoice portal
- **Cash:** Available at our office location

**Payment Due Date:** {{ \Carbon\Carbon::parse($sale->sale_date)->addDays(7)->format('F d, Y') }}
</x-mail::panel>
@else
<x-mail::panel>
<div style="background-color: #d4edda; padding: 12px; border-radius: 8px; text-align: center;">
    <strong style="color: #155724;">✓ Invoice Fully Paid</strong>
    <p style="margin: 8px 0 0 0; color: #155724;">Thank you for your payment!</p>
</div>
</x-mail::panel>
@endif

### Additional Information
- A PDF copy of this invoice is attached for your records
- Please retain this invoice for future reference
- For any questions, contact our support team at {{ config('mail.from.address') }}
- Late payments may incur additional fees

<div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #e9ecef;">
    <strong style="color: #495057;">Thank you for your business!</strong><br>
    <strong>{{ config('app.name') }} Team</strong><br>
    <small style="color: #6c757d;">Kyaw Family Scaffolding - Your Trusted Partner</small>
</div>

<small style="color: #6c757d; margin-top: 24px; display: block;">
    This is an automatically generated invoice. Please do not reply to this email.
    If you have any questions, please contact our support team.
</small>

</x-mail::message>