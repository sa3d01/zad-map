<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\UserRegisterationRequest;
use App\Http\Resources\ProviderLoginResourse;
use App\Models\DropDown;
use App\Models\User;
use App\Traits\UserBanksAndCarsTrait;
use App\Traits\UserPhoneVerificationTrait;
use Spatie\Permission\Models\Role;

class RegisterController extends MasterController
{
    use UserPhoneVerificationTrait,UserBanksAndCarsTrait;

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
        if ($user['type']!='USER'){
            if ($request['car']){
                $this->updateCarData($request->validated());
            }
            if ($request['banks']){
                $this->updateBankData($request->validated());
            }
            $user->update($request->validated());
        }
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
