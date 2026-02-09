<x-mail::message>
# Rent Invoice Created

Hello {{ $customer->name }},

Your rent invoice **#{{ $rent->rent_code }}** has been created successfully.

## Invoice Details:
- **Invoice Number:** {{ $rent->rent_code }}
- **Date:** {{ $rent->rent_date}}
- **Customer:** {{ $customer->name }}
- **Total Amount:** ${{ number_format($rent->total, 2) }}
- **Amount Paid:** ${{ number_format($rent->total_paid, 2) }}
- **Amount Due:** ${{ number_format($rent->total_due, 2) }}
- **Status:** {{ ucfirst($rent->status) }}

## Items Rented:
<x-mail::table>
| Product | Quantity | Unit Price | Total |
|---------|----------|------------|-------|
@foreach ($rent->items as $item)
| {{ $item->productVariant->product->name }} ({{ $item->productVariant->size }}) | {{ $item->rent_qty }} | ${{ number_format($item->unit_price, 2) }} | ${{ number_format($item->total, 2) }} |
@endforeach
</x-mail::table>

**Sub Total:** ${{ number_format($rent->sub_total, 2) }}  
**Transport:** ${{ number_format($rent->transport, 2) }}  
**Deposit:** ${{ number_format($rent->deposit, 2) }}  
**Discount:** ${{ number_format($rent->discount, 2) }}  
**Grand Total:** ${{ number_format($rent->total, 2) }}

<x-mail::button :url="route('rents.show', $rent->id)">
View Invoice Online
</x-mail::button>

Please find the attached PDF invoice for your records.

Thank you for your business!

Regards,  
{{ config('app.name') }}
</x-mail::message>