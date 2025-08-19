<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = new AppSetting();
        $setting->companyName = 'Comiparts';
        $setting->tagLine = 'Comiparts';
        $setting->address = 'Comiparts, 123 Main Street, City, Country';
        $setting->phone = '+12344555900';
        $setting->email = 'comiparts@email.com';
        $setting->website = 'https://comiparts';
        $setting->footer = '© 2025 Comiparts. All rights reserved.';

        $setting->save();
    }
}
