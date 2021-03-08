<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0;$i<3;$i++){
            User::create([
               'type'=>'Provider' ,
               'name'=>'Provider '.$i ,
               'phone'=>'057854874'.$i,
                'phone_verified_at'=>Carbon::now(),
                'password'=>'password',
                'location->lat'=>'24.725555'.$i,
                'location->lng'=>'47.102714'.$i,
                'city_id'=>1,
                'district_id'=>4,
                'approved'=>true,
                'approved_at'=>Carbon::now()
            ]);
        }
        for ($i=0;$i<4;$i++){
            User::create([
               'type'=>'FAMILY' ,
               'name'=>'FAMILY '.$i ,
               'phone'=>'057854875'.$i,
                'phone_verified_at'=>Carbon::now(),
                'password'=>'password',
                'location->lat'=>'24.725555'.$i,
                'location->lng'=>'47.102714'.$i,
                'city_id'=>1,
                'district_id'=>4,
                'approved'=>true,
                'approved_at'=>Carbon::now()
            ]);
        }
        for ($i=0;$i<10;$i++){
            User::create([
               'type'=>'DELIVERY' ,
               'name'=>'DELIVERY '.$i ,
               'phone'=>'057854873'.$i,
                'phone_verified_at'=>Carbon::now(),
                'password'=>'password',
                'location->lat'=>'24.725555'.$i,
                'location->lng'=>'47.102714'.$i,
                'city_id'=>1,
                'district_id'=>4,
                'approved'=>true,
                'approved_at'=>Carbon::now()
            ]);
        }
    }
}
