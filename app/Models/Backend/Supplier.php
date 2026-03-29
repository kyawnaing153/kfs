<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class Supplier extends Model
{
    use HasFactory, Notifiable;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'phone_number', 'company_name', 'status'
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }
}
