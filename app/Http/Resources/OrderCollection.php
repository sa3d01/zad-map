<?php

namespace App\Http\Resources;

use App\Models\PromoCode;
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
                $delivery['id']=$obj->delivery_id;
                $delivery['name']=$obj->delivery->name;
                $arr['delivery']=$delivery;
            }
            if ($obj['deliver_by']=='user') {
                $delivery_price=0;
            }else{
                $delivery_price=$obj->orderItems->first()->cartItem->product->user->delivery_price;
            }
            $promo_code = PromoCode::where('code', $obj->promo_code)->first();
            if ($promo_code) {
                $discount=$promo_code->discount_percent*($obj->price()+$delivery_price)/100;
                $arr['price'] = ($obj->price()+$delivery_price)-$discount;
            }else{
                $arr['price'] = $obj->price()+$delivery_price;
            }
            $data[]=$arr;
        }
        return $data;
    }
}
