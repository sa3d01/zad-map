<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Delivery;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

class DeliveryResourse extends JsonResource
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
        $car = Car::where('user_id', $this->id)->latest()->first();
        if (!$car) {
            $car_model = new Object_();
        } else {
            $car_model = new CarResourse($car);
        }
        return [
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
            'has_delivery'=> (int)$delivery->has_delivery,
            'delivery_price'=> (int)$delivery->delivery_price,
            'location' => $delivery->location,
            'image' => $delivery->image,
            'car' => $car_model,
            'banks' => new BankCollection($this->banks),
        ];
    }
}
