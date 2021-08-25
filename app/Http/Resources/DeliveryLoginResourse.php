<?php

namespace App\Http\Resources;

use App\Models\Car;
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
        $token = auth('api')->login(User::find($this->id));
        $car = Car::where('user_id', $this->id)->latest()->first();
        if (!$car) {
            $car_model = new Object_();
        } else {
            $car_model = new CarResourse($car);
        }
        $devices=[];
        $devices[]=$this->delivery->devices;
        $devices[]=$request['device.id'];
        $this->delivery->update([
            'devices' => $devices,
            'last_login_at' => Carbon::now(),
            'last_ip' => $request->ip(),
        ]);

        return [
            "user" => [
                'id' => (int)$this->id,
                'online' => $this->delivery->online,
                'rating' => (double)$this->averageRate(),
                'feedBacks' => $this->feedbacks(),
                'type' => $request['type'],
                'name' => $this->delivery->name,
                'phone' => $this->phone ?? "",
                'city' => [
                    'id' => $this->delivery->city ? (int)$this->delivery->city->id : 0,
                    'name' => $this->delivery->city ? $this->delivery->city->name : "",
                ],
                'district' => [
                    'id' => $this->delivery->district ? (int)$this->delivery->district->id : 0,
                    'name' => $this->delivery->district ? $this->delivery->district->name : "",
                ],
                'location'=>$this->delivery->location,
                'image' => $this->delivery->image,
                'has_delivery'=> 0,
                'delivery_price'=> 0,
                'car' => $car_model,
                'banks' => new BankCollection($this->delivery->banks),
            ],
            "settings" => [
                'approved' => $this->delivery->approved,
                'banned' => $this->delivery->banned,
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ];
    }
}
