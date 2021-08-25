<?php

namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

class ProviderResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $car = Car::where('user_id', $this->id)->latest()->first();
        if (!$car) {
            $car_model = new Object_();
        } else {
            $car_model = new CarResourse($car);
        }
        return [
            'id' => (int)$this->id,
            'online' => $this->provider->online,
            'rating' => (double)$this->averageRate(),
            'feedBacks' => $this->feedbacks(),
            'type' => $this->provider->type,
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
            'has_delivery'=> (int)$this->provider->has_delivery,
            'delivery_price'=> (int)$this->provider->delivery_price,
            'location' => $this->provider->location,
            'image' => $this->provider->image,
            'car' => $car_model,
            'banks' => new BankCollection($this->banks),
        ];
    }
}
