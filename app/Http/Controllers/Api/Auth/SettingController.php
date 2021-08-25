<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Auth\PasswordUpdateRequest;
use App\Http\Requests\Api\Auth\ProfileUpdateRequest;
use App\Http\Requests\Api\UploadImageRequest;
use App\Http\Resources\DeliveryLoginResourse;
use App\Http\Resources\ProviderLoginResourse;
use App\Http\Resources\UserLoginResourse;
use App\Models\Bank;
use App\Models\Car;
use App\Models\Delivery;
use App\Models\DropDown;
use App\Models\NormalUser;
use App\Models\Provider;
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
        $data['user_id'] = $user->id;
        $data['last_ip'] = $request->ip();
        $data['devices'] = $request['device.id'];
        if (request()->header('user_type') == 'USER') {
            $user->normal_user->update($data);
            return $this->sendResponse(new UserLoginResourse($user),'تم التعديل بنجاح :)');
        } elseif (request()->header('user_type') == 'PROVIDER' || request()->header('user_type') == 'FAMILY') {
            if ($request['banks']) {
                $this->updateBankData($request->validated(), $user, request()->header('user_type'));
            }
            if ($request['car']){
                $this->updateCarData($request->validated(),$user);
            }
            $user->provider->update($data);
            return $this->sendResponse(new ProviderLoginResourse($user),'سيتم مراجعة التعديلات من قبل الإدارة أولا :)');
        } elseif (request()->header('user_type') == 'DELIVERY') {
            if ($request['banks']) {
                $this->updateBankData($request->validated(), $user, request()->header('user_type'));
            }
            if ($request['car']){
                $this->updateCarData($request->validated(),$user);
            }
            $user->delivery->update($data);
            return $this->sendResponse(new DeliveryLoginResourse($user),'سيتم مراجعة التعديلات من قبل الإدارة أولا :)');
        } else {
            return $this->sendError('تأكد من اختيار نوع المستخدم');
        }
    }

    public function updatePassword(PasswordUpdateRequest $request): object
    {
        $user = auth('api')->user();
        if (Hash::check($request['old_password'], $user->password)) {
            $user->update([
                'password' => $request['new_password'],
            ]);
            if (request()->header('user_type')=='USER'){
                return $this->sendResponse(new UserLoginResourse($user));
            }elseif (request()->header('user_type')=='PROVIDER'){
                return $this->sendResponse(new ProviderLoginResourse($user));
            }else{
                return $this->sendResponse(new DeliveryLoginResourse($user));
            }
        }
        return $this->sendError('كلمة المرور غير صحيحة.');
    }

    public function updateOnlineStatus(): object
    {
        $user = auth('api')->user();
        if (request()->header('user_type')=='PROVIDER'){
            if ($user->provider->online==1){
                $user->provider->update([
                    'online' => 0,
                ]);
            }else{
                $user->provider->update([
                    'online' => 1,
                ]);
            }
            return $this->sendResponse(new ProviderLoginResourse($user));
        }else{
            if ($user->delivery->online==1){
                $user->delivery->update([
                    'online' => 0,
                ]);
            }else{
                $user->delivery->update([
                    'online' => 1,
                ]);
            }
            return $this->sendResponse(new DeliveryLoginResourse($user));
        }
    }

    public function uploadImage(UploadImageRequest $request):object
    {
        if ($request['type']=='avatar'){
            $user = auth('api')->user();
            if (request()->header('user_type')=='PROVIDER'){
                $user->provider->update([
                    'image'=>$request->file('image')
                ]);
                $image=$user->provider->image;
            }elseif (request()->header('user_type')=='DELIVERY'){
                $user->delivery->update([
                    'image'=>$request->file('image')
                ]);
                $image=$user->delivery->image;
            }else{
                $user->normal_user->update([
                    'image'=>$request->file('image')
                ]);
                $image=$user->normal_user->image;
            }

        }elseif ($request['type']=='transfer') {
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
