<x-mail::message>
# Return Receipt

Dear **{{ $customer->name }}**,

Thank you for returning the rented items. Please find attached the return receipt for your reference.

## Return Summary

**Rent Code:** {{ $rent->rent_code }}  
**Return Date:** {{ \Carbon\Carbon::parse($return->return_date)->format('d M Y') }}  
**Total Days:** {{ $return->total_days }} days  

## Financial Summary

**Total Rental Amount:** {{ number_format($return->total_rental_amount, 0) }} Ks  

@if(($return->transport ?? 0) > 0)
**Transport:** + {{ number_format($return->transport, 0) }} Ks  
@endif

@if($return->total_damage_fee > 0)
**Damage/Loss Fees:** + {{ number_format($return->total_damage_fee, 0) }} Ks  
@endif

@if(($rent->deposit ?? 0) > 0)
**Deposit:** - {{ number_format($rent->deposit, 0) }} Ks  
@endif

@php
    $totalPaymentByRentId = app(\App\Services\RentPaymentService::class)->getTotalPaymentByRentId($rent->id);
@endphp
**Total Payments Made:** - {{ number_format($totalPaymentByRentId, 0) }} Ks  

**Final Balance:**  
@if($return->refund_amount > 0)
**{{ number_format($return->refund_amount, 0) }} Ks (To be refunded)**
@elseif($return->collect_amount > 0)
**{{ number_format(abs($return->collect_amount), 0) }} Ks (Settled with Customer)**
@else
**0 Ks (Fully Settled)**
@endif

<x-mail::button :url="route('rents.returns.show', [$rent->id, $return->id])">
View Return Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>