<?php
namespace App\Helpers;
use Carbon\Carbon;

class AppHelper
{
    public static function formatDate($date, $format = 'Y-m-d')
    {
        return Carbon::parse($date)->format($format);
    }

    public static function instance()
    {
        return new AppHelper();
    }
}