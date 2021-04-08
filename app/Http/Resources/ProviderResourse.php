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
            'location' => $this->location,
            'image' => $this->image,
            'car' => $car_model,
            'banks' => new BankCollection($this->banks),
        ];
    }
}
