<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RentReturnItem extends Model
{
    protected $fillable = [
        'rent_return_id',
        'rent_item_id',
        'qty',
        'damage_fee',
        'notes',
    ];

    public function rentReturn()
    {
        return $this->belongsTo(RentReturn::class, 'rent_return_id');
    }

    public function rentItem()
    {
        return $this->belongsTo(RentItem::class, 'rent_item_id');
    }
}
