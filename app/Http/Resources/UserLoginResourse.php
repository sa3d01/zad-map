<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

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

        return [
            "user" => [
                'id' => (int)$this->id,
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
            ],
            "access_token" => [
                'token' => $token,
                'token_type' => 'Bearer',
            ],

        ];
    }
}
