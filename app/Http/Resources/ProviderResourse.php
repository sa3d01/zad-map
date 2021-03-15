<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id' => (int)$this->id,
            'rating' => 3.5,
            'feedBacks'=>[],
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
        ];
    }
}
