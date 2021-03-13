<?php

namespace Database\Seeders;

use App\Models\StoryPeriod;
use Illuminate\Database\Seeder;

class StorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StoryPeriod::create([
            'story_period'=>1,
            'story_price'=>2,
        ]);
        StoryPeriod::create([
            'story_period'=>2,
            'story_price'=>3,
        ]);
        StoryPeriod::create([
            'story_period'=>7,
            'story_price'=>5,
        ]);
        StoryPeriod::create([
            'story_period'=>30,
            'story_price'=>15,
        ]);
    }
}
