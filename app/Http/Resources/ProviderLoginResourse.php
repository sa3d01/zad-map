<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\NormalUser;
use App\Models\Provider;
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

    public function toArray($request)
    {
        $provider=Provider::where('user_id',$this->id)->first();
        $token = auth('api')->login(User::find($this->id));

        $devices[]=$request['device.id'];
        if ($provider->devices!=null && is_array($provider->devices)){
            $old_devices=$provider->devices;
        }elseif ($provider->devices!=null){
            $old_devices=(array)$provider->devices;
        }else{
            $old_devices=[];
        }
        $devices=array_merge($devices,$old_devices);

        $provider->update([
            'devices' => $devices,
            'last_login_at' => Carbon::now(),
            'last_ip' => $request->ip(),
        ]);
        return [
            "user" => [
                'id' => (int)$this->id,
                'online' => $provider->online,
                'rating' => (double)$this->averageRate(),
                'feedBacks' => $this->feedbacks(),
                'type' => $provider['type'],
                'name' => $provider->name,
                'phone' => $this->phone ?? "",
                'city' => [
                    'id' => $provider->city ? (int)$provider->city->id : 0,
                    'name' => $provider->city ? $provider->city->name : "",
                ],
                'district' => [
                    'id' => $provider->district ? (int)$provider->district->id : 0,
                    'name' => $provider->district ? $provider->district->name : "",
                ],
                'location'=>$provider->location,
                'image' => $provider->image,
                'has_delivery'=> (int)$provider->has_delivery,
                'delivery_price'=> (int)$provider->delivery_price,
                'car' => new Object_(),
                'banks' => new BankCollection($this->banks),
            ],
            "settings" => [
                'approved' => $provider->approved,
                'banned' => $provider->banned,
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ];
    }
}
