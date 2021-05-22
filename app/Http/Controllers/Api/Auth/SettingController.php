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
use App\Models\DropDown;
use App\Traits\UserBanksAndCarsTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Cast\Object_;

class SettingController extends MasterController
{
    use UserBanksAndCarsTrait;

    private function getDistrictId($district){
        $data=['class'=>'District','name'=>$district,'parent_id'=>request()->input('city_id')];
        $model=DropDown::where($data)->first();
        if (!$model){
            $model=DropDown::create($data);
        }
        return $model->id;
    }

    public function updateProfile(ProfileUpdateRequest $request): object
    {
        $user = auth('api')->user();
        $data=$request->validated();
        $data['district_id'] = $this->getDistrictId($request['district']);
        $data['last_ip'] = $request->ip();
        if ($user['type']!='USER'){
            if ($request['car']){
                $this->updateCarData($request->validated(),$user);
            }
            if ($request['banks']){
                $this->updateBankData($request->validated(),$user);
            }
            $user->update($data);
            return $this->sendResponse(new ProviderLoginResourse($user));
        }
        $user->update($data);
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

    public function updateOnlineStatus(): object
    {
        $user = auth('api')->user();
        if ($user['online']==1){
            $user->update([
                'online' => 0,
            ]);
        }else{
            $user->update([
                'online' => 1,
            ]);
        }
        return $this->sendResponse(new ProviderLoginResourse($user));
    }

    public function uploadImage(UploadImageRequest $request):object
    {
        if ($request['type']=='avatar'){
            $user = auth('api')->user();
            $user->update([
                'image'=>$request->file('image')
            ]);
            $image=$user->image;
        }elseif ($request['type']=='transfer') {
            $user = auth('api')->user();
            $file=$request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move('media/images/transfer/', $filename);
            $image= asset('media/images/transfer/').'/'.$filename;
        }else{
            $file=$request->file('image');
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move('media/images/car/', $filename);
            $image= asset('media/images/car/').'/'.$filename;
        }
        return $this->sendResponse([
            "image" => $image
        ]);
    }
}
