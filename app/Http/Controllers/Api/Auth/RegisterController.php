<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\UserRegisterationRequest;
use App\Models\Delivery;
use App\Models\DropDown;
use App\Models\NormalUser;
use App\Models\Provider;
use App\Models\User;
use App\Traits\UserBanksAndCarsTrait;
use App\Traits\UserPhoneVerificationTrait;

class RegisterController extends MasterController
{
    use UserPhoneVerificationTrait, UserBanksAndCarsTrait;

    public function register(UserRegisterationRequest $request): object
    {
        $data = $request->validated();
        $data['district_id'] = $this->getDistrictId($request['district']);
        $user = User::create($data);
        $user->refresh();
        $this->createPhoneVerificationCodeForUser($user);
        $data['user_id'] = $user->id;
        $data['last_ip'] = $request->ip();
        $data['devices'] = $request['device.id'];
        if ($request['type'] == 'USER') {
            NormalUser::create($data);
        } elseif ($request['type'] == 'PROVIDER' || $request['type'] == 'FAMILY') {
            Provider::create($data);
            if ($request['banks']) {
                $this->updateBankData($request->validated(), $user, $request['type']);
            }
            $user->update($request->validated());
        } elseif ($request['type'] == 'DELIVERY') {
            Delivery::create($data);
            if ($request['car']) {
                $this->updateCarData($request->validated(), $user);
            }
            if ($request['banks']) {
                $this->updateBankData($request->validated(), $user, $request['type']);
            }
            $user->update($request->validated());
        } else {
            return $this->sendError('تأكد من اختيار نوع المستخدم');
        }
        return $this->sendResponse([
            "phone" => $request["phone"]
        ]);
    }

    private function getDistrictId($district)
    {
        $data = ['class' => 'District', 'name' => $district, 'parent_id' => request()->input('city_id')];
        $model = DropDown::where($data)->first();
        if (!$model) {
            $model = DropDown::create($data);
        }
        return $model->id;
    }
}
