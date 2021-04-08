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
            $arr['type'] = $obj->type;
            $arr['name'] = $obj->name;
            $arr['location'] = $obj->location;
            $arr['image'] = $obj->image;
            $data[] = $arr;
        }
        return $data;
    }
}
