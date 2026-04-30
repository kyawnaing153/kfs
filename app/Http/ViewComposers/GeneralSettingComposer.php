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
            'companyName' => $query->where('key', 'company_name')->first()->value ?? '',
            'companyTagline' => $query->where('key', 'company_tagline')->first()->value ?? '',
            'email' => $query->where('key', 'email_address')->first()->value ?? '',
            'phone' => $query->where('key', 'phone_number')->first()->value ?? '',
            'address' => $query->where('key', 'address')->first()->value ?? '',
            'currencyName' => $query->where('key', 'currency_name')->first()->value ?? '',
            'currencySymbol' => $query->where('key', 'currency_symbol')->first()->value ?? '',
            'timezone' => $query->where('key', 'timezone')->first()->value ?? 'UTC',
            'logo' => asset('images/logo/kfs-logo-teal.svg'),
            'logoPath' => 'public/images/logo/kfs-logo-teal.svg',
            'favicon' =>  asset('images/logo/kfs-logo-teal.svg'),
            'copyright' =>  $query->where('key', 'copyright')->first()->value ?? '',
            'deposit_amount' => $query->where('key', 'deposit_amount')->first()->value ?? 5000,
        ];
        $view->with('settings', $settings);
    }
}
