<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\ProviderLoginResourse;
use App\Http\Resources\UserLoginResourse;
use App\Models\User;

class LoginController extends MasterController
{
    public function login(LoginRequest $request): object
    {
        $credentials = $request->only('phone', 'password');
        if ($request['type']=='USER' || $request['type']=='DELIVERY'){
            $user = User::where(['phone' => $request['phone'], 'type' => $request['type']])->first();
        }else{
            $types=['PROVIDER','FAMILY'];
            $user = User::where('phone' , $request['phone'])->whereIn('type',$types)->first();
            if (!$user) {
                return $this->sendError('هذا الحساب غير موجود.');
            }
//            if ($user->approved != 1){
//                return $this->sendError('هذا الحساب غير مفعل من قبل الإدارة.');
//            }
        }
        if (!$user) {
            return $this->sendError('هذا الحساب غير موجود.');
        }
        if (!$user->phone_verified_at) {
            return $this->sendError('هذا الحساب غير مفعل.',['phone_verified'=>false]);
        }
        if ($user->banned==1){
            return $this->sendError('تم حظرك من قبل إدارة التطبيق ..');
        }
        if (auth('api')->attempt($credentials)) {
            if ($user['type']!='USER'){
                return $this->sendResponse(new ProviderLoginResourse($user));
            }else{
                return $this->sendResponse(new UserLoginResourse($user));
            }
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
