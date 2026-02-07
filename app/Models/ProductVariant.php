<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'product_id',
        'size',
        'unit',
        'qty',
        'purchase_price',
        'sku',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(VariantPrice::class);
    }

    public function salePrice()
    {
        return $this->prices()->where('price_type', 'sale')->first();
    }

    public function rentPrices()
    {
        return $this->prices()->where('price_type', 'rent')->get();
    }
}
