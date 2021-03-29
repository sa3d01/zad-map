<?php

namespace Database\Seeders;

use App\Models\Page;
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
        Page::create([
            'type'=>'terms',
            'for'=>'user',
            'title'=>'الشروط والأحكام',
            'note'=>'الشروط والأحكام للمستخدم',
        ]);
        Page::create([
            'type'=>'terms',
            'for'=>'provider',
            'title'=>'الشروط والأحكام',
            'note'=>'الشروط والأحكام لمقدم الخدمه',
        ]);
        Page::create([
            'type'=>'terms',
            'for'=>'delivery',
            'title'=>'الشروط والأحكام',
            'note'=>'الشروط والأحكام للموصل',
        ]);
        Page::create([
            'type'=>'about',
            'for'=>'all',
            'title'=>'عن التطبيق',
            'note'=>'نص عن التطبيق',
        ]);
        Page::create([
            'type'=>'percent',
            'for'=>'all',
            'title'=>'عمولة التطبيق',
            'note'=>'نص عن عمولة التطبيق',
        ]);
        Setting::create([
            'mobile'=>'+9665xxxxxxxx',
            'email'=>'info@zad-map.com',
            'socials'=>[
                'twitter'=>'zad@twitter.com',
                'facebook'=>'zad@facebook.com',
                'snap'=>'zad@snap.com',
                'instagram'=>'zad@instagram.com',
            ]
        ]);
    }
}
