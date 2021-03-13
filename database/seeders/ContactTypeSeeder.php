<?php

namespace Database\Seeders;

use App\Models\ContactType;
use Illuminate\Database\Seeder;

class ContactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ContactType::create([
           'name'=>'شكوي'
        ]);
        ContactType::create([
           'name'=>'اقتراح'
        ]);
    }
}
