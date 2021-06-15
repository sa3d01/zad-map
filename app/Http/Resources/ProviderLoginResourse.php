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
    public function toArray($request)
    {
        $token = auth('api')->login(User::find($this->id));
        User::find($this->id)->update([
            'device' => [
                'id' => $request['device.id'],
                'os' => $request['device.os'],
            ],
            'last_login_at' => Carbon::now(),
            'last_ip' => $request->ip(),
        ]);
        $car = Car::where('user_id', $this->id)->latest()->first();
        if (!$car) {
            $car_model = new Object_();
        } else {
            $car_model = new CarResourse($car);
        }
        return [
            "user" => [
                'id' => (int)$this->id,
                'online' => $this->online,
                'rating' => (double)$this->averageRate(),
                'feedBacks' => $this->feedbacks(),
                'type' => $this->type,
                'name' => $this->name,
                'phone' => $this->phone ?? "",
                'city' => [
                    'id' => $this->city ? (int)$this->city->id : 0,
                    'name' => $this->city ? $this->city->name : "",
                ],
                'district' => [
                    'id' => $this->district ? (int)$this->district->id : 0,
                    'name' => $this->district ? $this->district->name : "",
                ],
                'location'=>$this->location,
                'image' => $this->image,
                'has_delivery'=> (int)$this->has_delivery,
                'delivery_price'=> (int)$this->delivery_price,
                'car' => $car_model,
                'banks' => new BankCollection($this->banks),
            ],
            "settings" => [
                'approved' => $this->approved,
                'banned' => $this->banned,
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],

        ];
    }
}
