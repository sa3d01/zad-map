<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProviderCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        foreach ($this as $obj) {
            $arr['id'] = (int)$obj->id;
            $arr['rating'] = (double)$obj->averageRate();
            $arr['type'] = $obj->provider->type;
            $arr['name'] = $obj->provider->name;
            $arr['location'] = $obj->provider->location;
            $arr['image'] = $obj->provider->image;
            $arr['online'] = $obj->provider->online;
            $data[] = $arr;
        }
        return $data;
    }
}
