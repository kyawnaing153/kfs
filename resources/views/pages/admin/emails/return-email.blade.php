<x-mail::message>

# Return Receipt

Hello **{{ $customer->name }}**,

Thank you for returning the rented items. Your return has been processed successfully. Please find the return receipt details below.

---

### Return Summary
<x-mail::table>
| | |
|:---|---:|
| **Customer Name** | {{ $customer->name }} |
| **Customer Email** | {{ $customer->email }} |
| **Rent Code** | {{ $rent->rent_code }} |
| **Rent Date** | {{ \Carbon\Carbon::parse($rent->rent_date)->format('M d, Y') }} |
| **Return Date** | {{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }} |
| **Total Rental Days** | {{ $return->total_days }} days |
</x-mail::table>

---

### Financial Breakdown
<x-mail::table>
| | |
|:---|---:|
| **Total Rental Amount** | {{ number_format($return->total_rental_amount, 0) }} Ks |
@if(($return->transport ?? 0) > 0)
| **Transportation** | + {{ number_format($return->transport, 0) }} Ks |
@endif
@if($return->total_damage_fee > 0)
| **Damage/Loss Fees** | + {{ number_format($return->total_damage_fee, 0) }} Ks |
@endif
@if(($rent->deposit ?? 0) > 0)
| **Deposit Deduction** | - {{ number_format($rent->deposit, 0) }} Ks |
@endif
@if(($return->total_payments ?? 0) > 0)
| **Payment Total** | - {{ number_format($return->total_payments, 0) }} Ks |
@endif
</x-mail::table>

---

### Payment Summary
<x-mail::panel>
<div style="background-color: #f8f9fa; padding: 0px; border-radius: 8px;">
    <table style="width: 100%;">
        <tr style="border-top: 2px solid #dee2e6;">
            <td style="padding: 12px 0 8px 0;"><strong style="font-size: 18px;">Final Balance:</strong></td>
            <td style="padding: 12px 0 8px 0; text-align: right;">
                @if($return->refund_amount > 0)
                    <strong style="font-size: 18px; color: #28a745;">{{ number_format($return->refund_amount, 0) }} Ks</strong>
                    <div style="font-size: 12px; color: #28a745;">(To be refunded)</div>
                @elseif($return->collect_amount > 0)
                    <strong style="font-size: 18px; color: #dc3545;">{{ number_format(abs($return->collect_amount), 0) }} Ks</strong>
                    <div style="font-size: 12px; color: #dc3545;">(To be collected)</div>
                @else
                    <strong style="font-size: 18px; color: #28a745;">0 Ks</strong>
                    <div style="font-size: 12px; color: #28a745;">(Fully Settled)</div>
                @endif
            </td>
        </tr>
    </table>
</div>
</x-mail::panel>

---

### Settlement Summary
<x-mail::panel>
@if($return->refund_amount > 0)
    <div style="background-color: #d4edda; padding: 16px; border-radius: 8px; border-left: 4px solid #28a745;">
        <strong style="color: #155724;">✓ Refund Due</strong>
        <p style="margin: 8px 0 0 0; color: #155724;">
            A refund of <strong>{{ number_format($return->refund_amount, 0) }} Ks</strong> will be processed to your original payment method.
        </p>
    </div>
@elseif($return->collect_amount > 0)
    <div style="background-color: #f8d7da; padding: 16px; border-radius: 8px; border-left: 4px solid #dc3545;">
        <strong style="color: #721c24;">⚠ Payment Required</strong>
        <p style="margin: 8px 0 0 0; color: #721c24;">
            An additional payment of <strong>{{ number_format(abs($return->collect_amount), 0) }} Ks</strong> is required to settle this return.
        </p>
    </div>
@else
    <div style="background-color: #d4edda; padding: 16px; border-radius: 8px; border-left: 4px solid #28a745;">
        <strong style="color: #155724;">✓ Fully Settled</strong>
        <p style="margin: 8px 0 0 0; color: #155724;">
            All payments have been settled. Thank you for your business!
        </p>
    </div>
@endif
</x-mail::panel>

<div style="margin-top: 32px; padding-top: 16px; border-top: 1px solid #e9ecef;">
    <strong style="color: #495057;">Thank you for choosing KFS!</strong><br>
    <strong>{{ config('app.name') }} Team</strong><br>
    <small style="color: #6c757d;">Kyaw Family Scaffolding - Your Trusted Partner</small>
</div>

<small style="color: #6c757d; margin-top: 24px; display: block;">
    This is an automatically generated receipt. Please do not reply to this email.
    If you have any questions, please contact our support team.
</small>
</x-mail::message>