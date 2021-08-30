<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Delivery;
use App\Models\Provider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

class DeliveryLoginResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $delivery=Delivery::where('user_id',$this->id)->first();
        $token = auth('api')->login(User::find($this->id));
        $car = Car::where('user_id', $this->id)->latest()->first();
        if (!$car) {
            $car_model = new Object_();
        } else {
            $car_model = new CarResourse($car);
        }
        $devices[]=$request['device.id'];
        if ($delivery->devices!=null && is_array($delivery->devices)){
            $old_devices=$delivery->devices;
        }elseif ($delivery->devices!=null){
            $old_devices=(array)$delivery->devices;
        }else{
            $old_devices=[];
        }
        if (!in_array($request['device.id'],$old_devices)){
            $devices=array_merge($devices,$old_devices);
        }else{
            $devices=$old_devices;
        }
        $delivery->update([
            'devices' => $devices,
            'last_login_at' => Carbon::now(),
            'last_ip' => $request->ip(),
        ]);
        return [
            "user" => [
                'id' => (int)$this->id,
                'online' => $delivery->online,
                'rating' => (double)$this->averageRate(),
                'feedBacks' => $this->feedbacks(),
                'type' => 'DELIVERY',
                'name' => $delivery->name,
                'phone' => $this->phone ?? "",
                'city' => [
                    'id' => $delivery->city ? (int)$delivery->city->id : 0,
                    'name' => $delivery->city ? $delivery->city->name : "",
                ],
                'district' => [
                    'id' => $delivery->district ? (int)$delivery->district->id : 0,
                    'name' => $delivery->district ? $delivery->district->name : "",
                ],
                'location'=>$delivery->location,
                'image' => $delivery->image,
                'has_delivery'=> 0,
                'delivery_price'=> 0,
                'car' => $car_model,
                'banks' => new BankCollection($delivery->user->banks),
            ],
            "settings" => [
                'approved' => $delivery->approved,
                'banned' => $delivery->banned,
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ];
    }
}
