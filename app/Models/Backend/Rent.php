<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Rent extends Model
{
    protected $fillable = [
        'rent_code',
        'customer_id',
        'rent_date',
        'sub_total',
        'discount',
        'deposit',
        'transport',
        'total',
        'total_paid',
        'total_due',
        'payment_type',
        'document',
        'status',
        'note',
    ];

    public function items()
    {
        return $this->hasMany(RentItem::class);
    }

    public function returns()
    {
        return $this->hasMany(RentReturn::class);
    }

    public function payments()
    {
        return $this->hasMany(RentPayment::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
