<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'terms->user' => 'الشروط والأحكام للمستخدم',
            'terms->provider' => 'الشروط والأحكام لمقدم الخدمه',
            'terms->delivery' => 'الشروط والأحكام للموصل',
            'verify_period'=>10
        ]);
    }
}
