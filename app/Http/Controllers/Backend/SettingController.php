<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SettingController extends Controller
{
    public function index()
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
            'copyright' => $query->where('key', 'copyright')->first()->value ?? '',
        ];

        return view('pages.admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_tagline' => 'nullable|string|max:500',
            'email_address' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'currency_name' => 'required|string|max:50',
            'currency_symbol' => 'required|string|max:10',
            'timezone' => 'required|string',
            'copyright' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            GeneralSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'display_name' => $this->getDisplayName($key)
                ]
            );
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully!');
    }

    private function getDisplayName($key)
    {
        $displayNames = [
            'company_name' => 'Company Name',
            'company_tagline' => 'Company Tagline',
            'email_address' => 'Email Address',
            'phone_number' => 'Phone Number',
            'address' => 'Address',
            'currency_name' => 'Currency Name',
            'currency_symbol' => 'Currency Symbol',
            'timezone' => 'Timezone',
            'copyright' => 'Copyright Text',
        ];

        return $displayNames[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
}
