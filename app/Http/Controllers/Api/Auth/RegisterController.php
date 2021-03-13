<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\UserRegisterationRequest;
use App\Models\DropDown;
use App\Models\User;
use App\Traits\UserPhoneVerificationTrait;
use Spatie\Permission\Models\Role;

class RegisterController extends MasterController
{
    use UserPhoneVerificationTrait;

    public function register(UserRegisterationRequest $request): object
    {
        $data = $request->validated();
        $data['last_ip'] = $request->ip();
        $data['district_id'] = $this->getDistrictId($request['district']);
        $user = User::create($data);
        $user->refresh();
        $role = Role::findOrCreate($user->type);
        $user->assignRole($role);
        $this->createPhoneVerificationCodeForUser($user);
        return $this->sendResponse([
            "phone" => $request["phone"]
        ]);
    }
    private function getDistrictId($district){
        $data=['class'=>'District','name'=>$district,'parent_id'=>request()->input('city_id')];
        $model=DropDown::where($data)->first();
        if (!$model){
            $model=DropDown::create($data);
        }
        return $model->id;
    }
}
