<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\DeliveryLoginResourse;
use App\Http\Resources\ProviderLoginResourse;
use App\Http\Resources\UserLoginResourse;
use App\Models\User;

class LoginController extends MasterController
{
    public function login(LoginRequest $request): object
    {
        $credentials = $request->only('phone', 'password');
        $user = User::where(['phone' => $request['phone']])->first();
        if (!$user) {
            return $this->sendError('هذا الحساب غير موجود.');
        }
        if (request()->header('userType')=='USER'){
            if (!$user->normal_user) {
                return $this->sendError('هذا الحساب غير موجود.');
            }
            if ($user->normal_user->banned==1){
                return $this->sendError('تم حظرك من قبل إدارة التطبيق ..');
            }
        }elseif (request()->header('userType')=='DELIVERY'){
            if (!$user->delivery) {
                return $this->sendError('هذا الحساب غير موجود.');
            }
            if ($user->delivery->banned==1){
                return $this->sendError('تم حظرك من قبل إدارة التطبيق ..');
            }
        }else{
            if (!$user->provider) {
                return $this->sendError('هذا الحساب غير موجود.');
            }
            if ($user->provider->banned==1){
                return $this->sendError('تم حظرك من قبل إدارة التطبيق ..');
            }
        }
        if (!$user->phone_verified_at) {
            return $this->sendError('هذا الحساب غير مفعل.',['phone_verified'=>false]);
        }
        if (auth('api')->attempt($credentials)) {
            if (request()->header('userType')=='PROVIDER' || request()->header('userType')=='FAMILY'){
                return $this->sendResponse(new ProviderLoginResourse($user));
            }elseif (request()->header('userType')=='DELIVERY'){
                return $this->sendResponse(new DeliveryLoginResourse($user));
            }else{
                return $this->sendResponse(new UserLoginResourse($user));
            }
        }
        return $this->sendError('كلمة المرور غير صحيحة.');
    }

    public function logout(): object
    {
        $user = auth('api')->user();

        if (request()->header('userType')=='PROVIDER' || request()->header('userType')=='FAMILY'){
            $user->provider->update([
                'devices'=>null
            ]);
        }elseif (request()->header('userType')=='DELIVERY'){
            $user->delivery->update([
                'devices'=>null
            ]);
        }else{
            $user->normal_user->update([
                'devices'=>null
            ]);
        }
        auth('api')->logout();
        return $this->sendResponse([], "Logged out successfully.");
    }
}
