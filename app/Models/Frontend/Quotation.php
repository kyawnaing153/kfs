<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_code',
        'customer_id',
        'type',
        'quotation_date',
        'rent_date',
        'rent_duration',
        'transport_required',
        'sub_total',
        'deposit',
        'transport',
        'discount',
        'total',
        'status',
        'transport_address',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
