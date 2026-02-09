<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'department',
        'salary',
        'address',
        'profile_picture',
        'status',
    ];

    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return asset('storage/Backend/staffs/' . $this->profile_picture);
        }

        return asset('Backend/img/profile.png');
    }
}
