<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RentReturn extends Model
{
    protected $fillable = [
        'rent_id',
        'refund_amount',
        'collect_amount',
        'return_date',
        'total_days',
        'transport',
        'return_image',
        'status',
        'note',
    ];

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function items()
    {
        return $this->hasMany(RentReturnItem::class, 'rent_return_id');
    }
}
