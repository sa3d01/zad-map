<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends ResourceCollection
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
            $arr['name'] = $obj->name;
            $arr['image'] = $obj->images[0];
            $arr['price'] = (double)$obj->price;
            $arr['in_cart'] = false;
            $data[] = $arr;
        }
        return $data;
    }
}
