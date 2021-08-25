<?php

namespace App\Traits;

use App\Models\PasswordReset;
use Carbon\Carbon;

trait UserPasswordResetTrait
{
    protected function createPasswordResetCodeForUser($user)
    {
        $data = [
            'user_type' => request()->header('user_type'),
            'phone' => $user->phone,
            'token' => 2021,//rand(1111, 9999),
            'expires_at' => Carbon::now()->addMinutes(10),
        ];
        PasswordReset::create($data);
        return $data;
    }
}
