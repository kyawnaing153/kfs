<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Backend\GeneralSetting;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // seed basic data to table
        $settingElemetns = [
            'company_name' => 'KFS',
            'compnay_tagline' => 'Rental Management System',
            'email_address' => 'ajeet@gmail.com',
            'phone_number' => '09977348597',
            'address' => 'Yangon, South Dagon',
            'currency_name' => 'MMK',
            'currency_symbol' => 'KS',
            'timezone' => 'Asia/Yangon',
            'starting_code' => '1',
            'logo' => 'dark-logo.png',
            'small_logo' => 'small-dark-logo.png',
            'favicon' => 'favicon.ico',
            'copyright' => 'Copyright © KFS'
        ];
        foreach ($settingElemetns as $key => $value) {
            GeneralSetting::create([
                'key' => $key,
                'display_name' => ucwords(str_replace("_", " ", $key)),
                'value' => $value
            ]);
        }
    }
}
