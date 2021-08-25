<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

class ProviderLoginResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    private function userData()
    {
        $devices[]=$this->normal_user->devices;
        $devices[]=request()->input('device.id');
        $this->normal_user->update([
            'devices' => $devices,
            'last_login_at' => Carbon::now(),
            'last_ip' => request()->ip(),
        ]);
        $arr['name'] = $this->normal_user->name;
        $arr['online'] = false;
        $arr['rating'] = 0;
        $arr['feedBacks'] = [];
        $arr['type'] = request()->input('type');
        $arr['phone'] =$this->phone;

        $arr['city'] = [
            'id' => $this->normal_user->city ? (int)$this->normal_user->city->id : 0,
            'name' => $this->normal_user->city ? $this->normal_user->city->name : "",
        ];
        $arr['district'] = [
            'id' => $this->normal_user->district ? (int)$this->normal_user->district->id : 0,
            'name' => $this->normal_user->district ? $this->normal_user->district->name : "",
        ];
        $arr['location'] =$this->normal_user->location;
        $arr['image'] =$this->normal_user->image;
        $arr['has_delivery'] =0;
        $arr['delivery_price'] =0;
        $arr['banks'] =[];
        return $arr;
    }
    public function toArray($request)
    {
        $token = auth('api')->login(User::find($this->id));
        $devices=[];
        $devices[]=$this->provider->devices;
        $devices[]=$request['device.id'];
        $this->provider->update([
            'devices' => $devices,
            'last_login_at' => Carbon::now(),
            'last_ip' => $request->ip(),
        ]);
        return [
            "user" => [
                'id' => (int)$this->id,
                'online' => $this->provider->online,
                'rating' => (double)$this->averageRate(),
                'feedBacks' => $this->feedbacks(),
                'type' => $request['type'],
                'name' => $this->provider->name,
                'phone' => $this->phone ?? "",
                'city' => [
                    'id' => $this->provider->city ? (int)$this->provider->city->id : 0,
                    'name' => $this->provider->city ? $this->provider->city->name : "",
                ],
                'district' => [
                    'id' => $this->provider->district ? (int)$this->provider->district->id : 0,
                    'name' => $this->provider->district ? $this->provider->district->name : "",
                ],
                'location'=>$this->provider->location,
                'image' => $this->provider->image,
                'has_delivery'=> (int)$this->provider->has_delivery,
                'delivery_price'=> (int)$this->provider->delivery_price,
                'car' => new Object_(),
                'banks' => new BankCollection($this->provider->banks),
            ],
            "settings" => [
                'approved' => $this->provider->approved,
                'banned' => $this->provider->banned,
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],

        ];
    }
}
