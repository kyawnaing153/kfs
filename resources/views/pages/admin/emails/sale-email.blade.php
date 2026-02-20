<x-mail::message>
# Sale Invoice Created

Hello {{ $customer->name }},

Your sale invoice **#{{ $sale->sale_code }}** has been created successfully.

## Invoice Details:
- **Invoice Number:** {{ $sale->sale_code }}
- **Date:** {{ $sale->sale_date}}
- **Customer:** {{ $customer->name }}
- **Total Amount:** ${{ number_format($sale->total, 2) }}
- **Amount Paid:** ${{ number_format($sale->total_paid, 2) }}
- **Amount Due:** ${{ number_format($sale->total_due, 2) }}
- **Status:** {{ ucfirst($sale->status) }}

## Items Sold:
<x-mail::table>
| Product | Quantity | Unit Price | Total |
|---------|----------|------------|-------|
@foreach ($sale->items as $item)
| {{ $item->productVariant->product->product_name }} ({{ $item->productVariant->size }}) | {{ $item->sale_qty }} | ${{ number_format($item->unit_price, 2) }} | ${{ number_format($item->total, 2) }} |
@endforeach
</x-mail::table>

**Sub Total:** ${{ number_format($sale->sub_total, 2) }}  
**Transport:** ${{ number_format($sale->transport, 2) }}  
**Discount:** ${{ number_format($sale->discount, 2) }}  
**Grand Total:** ${{ number_format($sale->total, 2) }}

<x-mail::button :url="route('sales.show', $sale->id)">
View Invoice Online
</x-mail::button>

Please find the attached PDF invoice for your records.

Thank you for your business!

Regards,  
{{ config('app.name') }}
</x-mail::message>