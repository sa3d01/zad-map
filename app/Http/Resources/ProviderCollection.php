<?php

namespace App\Http\Resources;

use App\Models\Provider;
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
            $provider=Provider::where('user_id',$obj->id)->first();
            $arr['id'] = (int)$obj->id;
            $arr['rating'] = (double)$obj->averageRate();
            $arr['type'] = $provider->id;
            $arr['name'] = $provider->id;
            $arr['location'] = $provider->id;
            $arr['image'] = $provider->id;
            $arr['online'] = $provider->id;
            $data[] = $arr;
        }
        return $data;
    }
}
