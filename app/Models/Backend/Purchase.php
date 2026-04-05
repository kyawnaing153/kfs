<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_code',
        'supplier_id',
        'purchase_date',
        'sub_total',
        'transport',
        'discount',
        'tax',
        'total_amount',
        'payment_status',
        'status',
        'notes',
        'user_id',
    ];

    // Constants
    const PAYMENT_UNPAID = 0;
    const PAYMENT_PAID = 1;

    const STATUS_PENDING = 0;
    const STATUS_DELIVERED = 1;

    // Relationships
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessors
    public function getPaymentStatusTextAttribute()
    {
        return $this->payment_status == self::PAYMENT_PAID ? 'Paid' : 'Unpaid';
    }

    public function getStatusTextAttribute()
    {
        return $this->status == self::STATUS_DELIVERED ? 'Delivered' : 'Pending';
    }

    public function getStatusBadgeClassAttribute()
    {
        return $this->status == self::STATUS_DELIVERED
            ? 'bg-green-100 text-green-800'
            : 'bg-yellow-100 text-yellow-800';
    }

    public function getPaymentBadgeClassAttribute()
    {
        return $this->payment_status == self::PAYMENT_PAID
            ? 'bg-green-100 text-green-800'
            : 'bg-red-100 text-red-800';
    }

    // Helper Methods
    public function calculateTotals()
    {
        $this->sub_total = $this->items->sum('total');
        $this->total_amount = $this->sub_total
            + ($this->transport ?? 0)
            - $this->discount
            + $this->tax;
        $this->saveQuietly();
    }

    public function updateStock()
    {
        foreach ($this->items as $item) {
            $variant = $item->productVariant;
            if ($variant) {
                $variant->qty += $item->received_qty;
                $variant->save();
            }
        }
    }


    public static function generatePurchaseCode(): string
    {
        $prefix = 'PUR-' . date('Ym');

        $lastPurchase = self::where('purchase_code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPurchase) {
            $lastNumber = (int) substr($lastPurchase->purchase_code, -2);
            $newNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '01';
        }

        return $prefix . '-' . $newNumber;
    }

    protected static function booted()
    {
        static::creating(function ($purchase) {
            if (empty($purchase->purchase_code)) {
                $purchase->purchase_code = self::generatePurchaseCode();
            }
        });
    }
}
