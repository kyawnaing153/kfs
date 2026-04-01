<x-mail::message>
# Rent Invoice Created

Hello {{ $customer->name }},

Your rent invoice has been generated successfully. Please review the details below.

---

### Invoice Summary
<x-mail::table>
| | |
|:---|---:|
| **Invoice No** | {{ $rent->rent_code }} |
| **Rent Date** | {{ \Carbon\Carbon::parse($rent->rent_date)->format('F d, Y') }} |
| **Customer Name** | {{ $customer->name }} |
| **Customer Email** | {{ $customer->email }} |
</x-mail::table>

---

### Rented Items
<x-mail::table>
| Product Details | Quantity | Unit Price | Total |
|-----------------|----------|------------|-------|
@foreach ($rent->items as $item)
| **{{ $item->productVariant->product->product_name }}**<br><small style="color: #6c757d;">Size: {{ $item->productVariant->size }}</small> | {{ $item->rent_qty }} | ${{ number_format($item->unit_price, 2) }} | ${{ number_format($item->total, 2) }} |
@endforeach
</x-mail::table>

---

### Payment Breakdown
<x-mail::table>
| | |
|:---|---:|
| **Subtotal** | ${{ number_format($rent->sub_total, 2) }} |
| **Transportation** | ${{ number_format($rent->transport, 2) }} |
| **Security Deposit** | ${{ number_format($rent->deposit, 2) }} |
@if($rent->discount > 0)
| **Discount** | <span style="color: #28a745;">-${{ number_format($rent->discount, 2) }}</span> |
@endif
| **Grand Total** | **${{ number_format($rent->total, 2) }}** |
</x-mail::table>

### Amount Summary
<x-mail::panel>
<div style="background-color: #f8f9fa; padding: 16px; border-radius: 8px;">
    <table style="width: 100%;">
        <tr>
            <td style="padding: 8px 0;"><strong>Total Amount:</strong></td>
            <td style="padding: 8px 0; text-align: right;">${{ number_format($rent->total, 2) }}</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;"><strong>Amount Paid:</strong></td>
            <td style="padding: 8px 0; text-align: right; color: #28a745;">${{ number_format($rent->total_paid, 2) }}</td>
        </tr>
        <tr style="border-top: 2px solid #dee2e6;">
            <td style="padding: 12px 0 8px 0;"><strong style="font-size: 18px;">Amount Due:</strong></td>
            <td style="padding: 12px 0 8px 0; text-align: right;">
                <strong style="font-size: 20px; color: #dc3545;">${{ number_format($rent->total_due, 2) }}</strong>
            </td>
        </tr>
    </table>
</div>
</x-mail::panel>

<div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #e9ecef;">
    <strong style="color: #495057;">Regards,</strong><br>
    <strong>{{ config('app.name') }} Team</strong><br>
    <small style="color: #6c757d;">Professional Rental Services</small>
</div>

<small style="color: #6c757d; margin-top: 24px; display: block;">
    This is an automatically generated invoice. Please do not reply to this email.
    If you have any questions, please contact our support team.
</small>
</x-mail::message>