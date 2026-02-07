<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class RentPayment extends Model
{
    protected $fillable = [
        'rent_id',
        'amount',
        'payment_method',
        'payment_date',
        'payment_for',
        'period_start',
        'period_end',
        'note',
    ];

    protected $casts = [
        'amount' => 'float',
        'payment_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    /**
     * Get the rent that owns the payment
     */
    public function rent()
    {
        return $this->belongsTo(Rent::class);
    }

    /**
     * Scope a query to filter by payment method
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope a query to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by rent
     */
    public function scopeForRent($query, $rentId)
    {
        return $query->where('rent_id', $rentId);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Ks ' . number_format($this->amount, 1);
    }

    /**
     * Get payment method badge color
     */
    public function getPaymentMethodBadgeAttribute()
    {
        $colors = [
            'cash' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'bank_transfer' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'card' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            'mobile_payment' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'cheque' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
        ];

        return $colors[$this->payment_method] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
}