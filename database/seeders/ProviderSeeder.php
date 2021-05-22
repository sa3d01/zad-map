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
        for ($i=0;$i<2;$i++){
            User::create([
               'type'=>'Provider' ,
               'name'=>'New Provider '.$i ,
               'phone'=>'057854871'.$i,
                'phone_verified_at'=>Carbon::now(),
                'password'=>'password',
                'location->lat'=>'24.725555'.$i,
                'location->lng'=>'47.102714'.$i,
                'city_id'=>1,
                'district_id'=>4,
                'approved'=>true,
                'has_delivery'=>true,
                'delivery_price'=>5,
                'approved_at'=>Carbon::now()
            ]);
        }
        for ($i=0;$i<4;$i++){
            User::create([
               'type'=>'FAMILY' ,
               'name'=>'New FAMILY '.$i ,
               'phone'=>'057854872'.$i,
                'phone_verified_at'=>Carbon::now(),
                'password'=>'password',
                'location->lat'=>'24.725555'.$i,
                'location->lng'=>'47.102714'.$i,
                'city_id'=>1,
                'district_id'=>4,
                'has_delivery'=>true,
                'delivery_price'=>5,
                'approved'=>true,
                'approved_at'=>Carbon::now()
            ]);
        }
        for ($i=0;$i<4;$i++){
            User::create([
               'type'=>'DELIVERY' ,
               'name'=>'New DELIVERY '.$i ,
               'phone'=>'057854879'.$i,
                'phone_verified_at'=>Carbon::now(),
                'password'=>'password',
                'location->lat'=>'24.725555'.$i,
                'location->lng'=>'47.102714'.$i,
                'city_id'=>1,
                'district_id'=>4,
                'has_delivery'=>true,
                'delivery_price'=>5,
                'approved'=>true,
                'approved_at'=>Carbon::now()
            ]);
        }
    }
}
