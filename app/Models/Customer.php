<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Backend\Rent;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;
    
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password','phone_number', 'profile_picture', 'company_name', 'address', 'status'
    ];

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function customerprofile()
    {
        if (!empty($this->profile_picture)) {
            return asset('Backend/img/customer/' . $this->profile_picture);
        }
        return asset('Backend/img/profile.png');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }
}
