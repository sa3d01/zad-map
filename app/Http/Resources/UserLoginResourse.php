<?php

namespace App\Http\Resources;

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
        $token = auth('api')->login(User::find($this->id));
        $devices[]=$this->normal_user->devices;
        $devices[]=$request['device.id'];
        $this->normal_user->update([
            'devices' => $devices,
            'last_login_at' => Carbon::now(),
            'last_ip' => $request->ip(),
        ]);
        return [
            "user" => [
                'id' => (int)$this->id,
                'type' => $request['type'],
                'name' => $this->normal_user->name,
                'phone' => $this->phone ?? "",
                'city' => [
                    'id' => $this->normal_user->city ? (int)$this->normal_user->city->id : 0,
                    'name' => $this->normal_user->city ? $this->city->normal_user->name : "",
                ],
                'district' => [
                    'id' => $this->normal_user->district ? (int)$this->normal_user->district->id : 0,
                    'name' => $this->normal_user->district ? $this->normal_user->district->name : "",
                ],
                'location'=>$this->normal_user->location,
                'image' => $this->normal_user->image,
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],

        ];
    }
}
