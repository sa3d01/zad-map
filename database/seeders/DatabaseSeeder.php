<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingSeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            SliderSeeder::class,
            StorySeeder::class,
            CategorySeeder::class,
            ContactTypeSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
