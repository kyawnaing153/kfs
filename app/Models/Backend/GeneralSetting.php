<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'key', 'display_name', 'value'
    ];
}
