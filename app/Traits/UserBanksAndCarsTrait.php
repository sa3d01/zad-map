<?php

namespace App\Traits;

use App\Models\Bank;
use App\Models\Car;

trait UserBanksAndCarsTrait
{


    protected function updateCarData($request)
    {
        $car = Car::where('user_id', auth('api')->id())->latest()->first();
        if (!$car) {
            $car = Car::create([
                'user_id' => auth('api')->id()
            ]);
        }
        $car->update([
            'brand' => $request['car']['brand'],
            'note' => $request['car']['note'],
            'color' => $request['car']['color'],
            'year' => $request['car']['year'],
            'identity' => $request['car']['identity'],
            'end_insurance_date' => $request['car']['end_insurance_date'],
        ]);
    }

    protected function updateBankData($request)
    {
        $user = auth('api')->user();
        $banks=$user->banks;
        foreach ($banks as $bank){
            $bank->delete();
        }
        foreach ($request['banks'] as $bank) {
            $bank=Bank::create([
                'user_id' => auth('api')->id(),
                'name' => $bank['name'],
                'account_number' => $bank['account_number'],
            ]);
            $bank->refresh();
        }
        $user->refresh();

    }

}
