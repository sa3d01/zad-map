<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\PasswordUpdateRequest;
use App\Http\Requests\Api\Auth\ProfileUpdateRequest;
use App\Http\Requests\Api\UploadImageRequest;
use App\Http\Resources\ProviderLoginResourse;
use App\Http\Resources\UserLoginResourse;
use App\Models\Bank;
use App\Models\Car;
use Illuminate\Support\Facades\Hash;

class SettingController extends MasterController
{
    function updateCarData($request)
    {
        $car=Car::where('user_id',auth('api')->id())->latest()->first();
        if (!$car){
            $car=Car::create([
                'user_id'=> auth('api')->id()
            ]);
        }
        $car->update([
            'brand'=>$request['car']['brand'],
            'color'=>$request['car']['color'],
            'year'=>$request['car']['year'],
            'identity'=>$request['car']['identity'],
            'end_insurance_date'=>$request['car']['end_insurance_date'],
        ]);
    }
    function updateBankData($request)
    {
        foreach ($request['banks'] as $bank){
            $old_bank_name=Bank::where(['user_id'=>auth('api')->id(),'name'=>$bank['name']])->latest()->first();
            if ($old_bank_name){
                $old_bank_name->update([
                    'user_id'=> auth('api')->id(),
                    'name'=> $bank['name'],
                    'account_number'=> $bank['account_number'],
                ]);
            } else{
                Bank::create([
                    'user_id'=> auth('api')->id(),
                    'name'=> $bank['name'],
                    'account_number'=> $bank['account_number'],
                ]);
            }
        }
    }

    public function updateProfile(ProfileUpdateRequest $request): object
    {
        $user = auth('api')->user();
        if ($user['type']!='USER'){
            if ($request['car']){
                $this->updateCarData($request->validated());
            }
            if ($request['banks']){
                $this->updateBankData($request->validated());
            }
            $user->update($request->validated());
            return $this->sendResponse(new ProviderLoginResourse($user));
        }
        $user->update($request->validated());
        return $this->sendResponse(new UserLoginResourse($user));
    }

    public function updatePassword(PasswordUpdateRequest $request): object
    {
        $user = auth('api')->user();
        if (Hash::check($request['old_password'], $user->password)) {
            $user->update([
                'password' => $request['new_password'],
            ]);
            if ($user['type']!='USER'){
                return $this->sendResponse(new ProviderLoginResourse($user));
            }else{
                return $this->sendResponse(new UserLoginResourse($user));
            }
        }
        return $this->sendError('كلمة المرور غير صحيحة.');
    }

    public function uploadImage(UploadImageRequest $request):object
    {
        $user = auth('api')->user();
        if ($request['type']=='avatar'){
            $user->update([
                'image'=>$request->file('image')
            ]);
            $image=$user->image;
        }else{
            $image_type=$request['type'];
            $car=Car::where('user_id',auth('api')->id())->latest()->first();
            if (!$car){
                $car=Car::create([
                   'user_id'=> auth('api')->id()
                ]);
            }
            $car->update([
                $image_type=>$request->file('image')
            ]);
            $image=$car->$image_type;

        }
        return $this->sendResponse([
            "image" => $image
        ]);
    }
}
