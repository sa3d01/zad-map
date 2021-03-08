<?php

namespace Database\Seeders;

use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0;$i<5;$i++){
            Slider::create([
                'start_date'=>Carbon::now()->timestamp,
                'end_date'=>Carbon::now()->addDays(7)->timestamp,
                'title'=>'عنوان',
                'note'=>'تفاصيل',
                'image'=>'default.png',
            ]);
        }
    }
}
