<?php

namespace App\Traits;

use App\Models\PhoneVerificationCode;
use App\Models\Setting;
use Carbon\Carbon;

trait UserPhoneVerificationTrait
{

    protected function createPhoneVerificationCodeForUser($user)
    {
        $data = [
            'user_type' => request()->header('user_type'),
            'user_id' => $user->id,
            'phone' => $user->phone,
            'code' => 2021,//rand(1111, 9999),
            'expires_at' => Carbon::now()->addMinutes(Setting::value('verify_period')),
        ];
        PhoneVerificationCode::create($data);
        //todo : send sms
        return $data;
    }

}
