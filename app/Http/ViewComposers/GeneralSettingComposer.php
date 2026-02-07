<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Backend\GeneralSetting;

class GeneralSettingComposer
{
    public function compose($view)
    {
        $query = GeneralSetting::all();
        $settings = [
            'compnayName' => $query->where('key', 'company_name')->first()->value,
            'companyTagline' => $query->where('key', 'compnay_tagline')->first()->value,
            'email' => $query->where('key', 'email_address')->first()->value,
            'phone' => $query->where('key', 'phone_number')->first()->value,
            'address' => $query->where('key', 'address')->first()->value,
            'currencyName' => $query->where('key', 'currency_name')->first()->value,
            'currencySymbol' => $query->where('key', 'currency_symbol')->first()->value,
            'timezone' => $query->where('key', 'timezone')->first()->value,
            'startingCode' => $query->where('key', 'starting_purchase_code')->first()->value,

            'logo' =>$query->where('key', 'logo')->first()->value? asset('img') . '/' . $query->where('key', 'logo')->first()->value : asset('img/logo-black.svg'),

            'smallLogo' =>$query->where('key', 'small_logo')->first()->value ? asset('img') . '/' . $query->where('key', 'small_logo')->first()->value : asset('img/small-dark-logo.png'),

            'favicon' =>  asset('/') . $query->where('key', 'favicon')->first()->value,
            'copyright' =>  $query->where('key', 'copyright')->first()->value ?? '',

        ];
        $view->with('settings', $settings);
    }
}