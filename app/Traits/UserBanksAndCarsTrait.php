<?php

namespace App\Traits;

use App\Models\Bank;
use App\Models\Car;

trait UserBanksAndCarsTrait
{


    protected function updateCarData($request,$user)
    {
        $car = Car::where('user_id', $user->id)->latest()->first();
        if (!$car) {
            $car = Car::create([
                'user_id' => $user->id
            ]);
        }
        $car->update([
            'brand' => $request['car']['brand'],
            'note' => $request['car']['note'],
            'color' => $request['car']['color'],
            'year' => $request['car']['year'],
            'identity' => $request['car']['identity'],
            'end_insurance_date' => $request['car']['end_insurance_date'],
            'insurance_image' => $request['car']['insurance_image'],
            'identity_image' => $request['car']['identity_image'],
            'drive_image' => $request['car']['drive_image'],
        ]);
    }

    protected function updateBankData($request,$user,$user_type)
    {
        $banks=$user->banks();
        foreach ($banks as $bank){
            $bank->delete();
        }
        foreach ($request['banks'] as $bank) {
            $bank=Bank::create([
                'user_id' =>$user->id,
                'user_type' =>$user_type,
                'name' => $bank['name'],
                'account_number' => $bank['account_number'],
                'account_name' => $bank['account_name'],
            ]);
            $bank->refresh();
        }
        $user->refresh();

    }

}
