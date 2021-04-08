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
            'color' => $request['car']['color'],
            'year' => $request['car']['year'],
            'identity' => $request['car']['identity'],
            'end_insurance_date' => $request['car']['end_insurance_date'],
        ]);
    }

    protected function updateBankData($request)
    {
        foreach ($request['banks'] as $bank) {
            $old_bank_name = Bank::where(['user_id' => auth('api')->id(), 'name' => $bank['name']])->latest()->first();
            if ($old_bank_name) {
                $old_bank_name->update([
                    'user_id' => auth('api')->id(),
                    'name' => $bank['name'],
                    'account_number' => $bank['account_number'],
                ]);
            } else {
                Bank::create([
                    'user_id' => auth('api')->id(),
                    'name' => $bank['name'],
                    'account_number' => $bank['account_number'],
                ]);
            }
        }
    }

}
