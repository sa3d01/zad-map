<?php

namespace Database\Seeders;

use App\Models\DropDown;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DropDown::create([
            'class'=>'City',
            'name'=>'الرياض',
        ]);
        DropDown::create([
            'class'=>'City',
            'name'=>'جدة',
        ]);
        DropDown::create([
            'class'=>'City',
            'name'=>'الدمام',
        ]);
    }
}
