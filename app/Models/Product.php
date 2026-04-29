<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_type',
        'description',
        'thumb',
        'status',
        'is_feature',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function getThumbUrlAttribute()
    {
        return $this->thumb ? asset('storage/' . $this->thumb) : asset('Backend/img/profile.png');
    }

    public function getRentPriceAttribute()
    {
        $variant = $this->variants->first();
        if (!$variant) return 0;

        $price = $variant->prices->where('price_type', 'rent')->first();
        return $price->price ?? 0;
    }

    public function getLowStockAttribute()
    {
        $totalStock = $this->variants->sum('qty');
        return $totalStock < 5; // Consider low stock if total quantity is less than 5
    }
}
