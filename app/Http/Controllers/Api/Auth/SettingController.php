<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\PasswordUpdateRequest;
use App\Http\Requests\Api\Auth\ProfileUpdateRequest;
use App\Http\Requests\Api\UploadImageRequest;
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
            'delivery_price'=>$request['car']['delivery_price'],
            'brand'=>$request['car']['brand'],
            'color'=>$request['car']['color'],
            'year'=>$request['car']['year'],
            'identity'=>$request['car']['identity'],
            'end_insurance_date'=>$request['car']['end_insurance_date'],
        ]);
    }
    function updateBankData($request)
    {
        $bank=Bank::where('user_id',auth('api')->id())->latest()->first();
        if (!$bank){
            $bank=Bank::create([
                'user_id'=> auth('api')->id()
            ]);
        }
        $bank->update([
            'name'=>$request['bank']['name'],
            'account_number'=>$request['bank']['account_number'],
        ]);
    }
    public function updateProfile(ProfileUpdateRequest $request): object
    {
        $user = auth('api')->user();
        if ($user['type']=='PROVIDER'){
            $this->updateCarData($request->validated());
            $this->updateBankData($request->validated());
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
            return $this->sendResponse(new UserLoginResourse($user));
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
