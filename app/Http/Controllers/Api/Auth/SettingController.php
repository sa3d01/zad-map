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
use App\Traits\UserBanksAndCarsTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;

class SettingController extends MasterController
{
    use UserBanksAndCarsTrait;

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
        }elseif ($request['type']=='transfer') {
            $file=$request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move('media/images/transfer/', $filename);
            $image= asset('media/images/transfer/').'/'.$filename;
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
