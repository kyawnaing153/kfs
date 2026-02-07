<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use App\Models\Backend\Rent;
use App\Models\ProductVariant;

class RentItem extends Model
{
    protected $fillable = [
        'rent_id',
        'product_variant_id',
        'rent_qty',
        'return_qty',
        'unit',
        'unit_price',
        'total',
    ];

    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
