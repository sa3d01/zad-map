<?php

namespace App\Http\Resources;

use App\Models\NormalUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $normal_user=NormalUser::where('user_id',$this->id)->first();
        $token = auth('api')->login(User::find($this->id));

        $devices[]=$request['device.id'];
        if ($normal_user->devices!=null && is_array($normal_user->devices)){
            $old_devices=$normal_user->devices;
        }elseif ($normal_user->devices!=null){
            $old_devices=(array)$normal_user->devices;
        }else{
            $old_devices=[];
        }
        $devices=array_merge($devices,$old_devices);

        $normal_user->update([
            'devices' => $devices,
            'last_login_at' => Carbon::now(),
            'last_ip' => $request->ip(),
        ]);
        return [
            "user" => [
                'id' => (int)$this->id,
                'type' => 'USER',
                'name' => $normal_user->name,
                'phone' => $this->phone ?? "",
                'city' => [
                    'id' => $normal_user->city ? (int)$normal_user->city->id : 0,
                    'name' => $normal_user->city ? $normal_user->city->name : "",
                ],
                'district' => [
                    'id' => $normal_user->district ? (int)$normal_user->district->id : 0,
                    'name' => $normal_user->district ? $normal_user->district->name : "",
                ],
                'location'=>$normal_user->location,
                'image' => $normal_user->image,
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],

        ];
    }
}
