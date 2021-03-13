<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\UserLoginResourse;
use App\Models\User;

class LoginController extends MasterController
{
    public function login(LoginRequest $request): object
    {
        //todo: check if provider approved
        $credentials = $request->only('phone', 'password');
        $user = User::where(['phone' => $request['phone'], 'type' => $request['type']])->first();
        if (!$user) {
            return $this->sendError('هذا الحساب غير موجود.');
        }
        if (!$user->phone_verified_at) {
            return $this->sendError('هذا الحساب غير مفعل.');
        }
        if (auth('api')->attempt($credentials)) {
            return $this->sendResponse(new UserLoginResourse($user));
        }
        return $this->sendError('كلمة المرور غير صحيحة.');
    }

    public function logout(): object
    {
        $user = auth('api')->user();
        $user->update([
            'device' => [
                'id' => null,
                'os' => null,
            ]
        ]);
        auth('api')->logout();
        return $this->sendResponse([], "Logged out successfully.");
    }
}
