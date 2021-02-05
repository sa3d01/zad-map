<?php

namespace Database\Seeders;

use App\Models\DropDown;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DropDown::create([
            'class'=>'District',
            'name'=>'الملز',
            'parent_id'=>1,
        ]);
        DropDown::create([
            'class'=>'District',
            'name'=>'البحر',
            'parent_id'=>2,
        ]);
        DropDown::create([
            'class'=>'District',
            'name'=>'الكفار',
            'parent_id'=>3,
        ]);
    }
}
