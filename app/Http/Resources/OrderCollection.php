<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $data = [];
        foreach ($this as $obj) {
            $arr['id'] = (int)$obj->id;
            $arr['status'] = $obj->status;
            $arr['user']['id'] = $obj->user_id;
            $arr['user']['name'] = $obj->user->name;
            $arr['provider']['id'] = $obj->provider_id;
            $arr['provider']['name'] = $obj->provider->name;
            $arr['price'] = $obj->price();
            $data[]=$arr;
        }
        return $data;
    }
}
