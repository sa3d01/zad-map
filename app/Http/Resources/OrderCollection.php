<?php

namespace App\Http\Resources;

use App\Models\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;
use phpDocumentor\Reflection\Types\Object_;

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
            if ($obj->delivery_id==null){
                $arr['delivery']=new Object_();
            }else{
                $arr['delivery']['id'] = $obj->delivery_id;
                $arr['delivery']['name'] = $obj->delivery->name;
            }
            if ($obj['deliver_by']=='delivery')
            {
                $delivery_price=Setting::value('delivery_price');
            }else{
                $delivery_price=$obj->orderItems->first()->cartItem->product->delivery_price;
            }
            $arr['price'] = $obj->price()+$delivery_price;
            $data[]=$arr;
        }
        return $data;
    }
}
