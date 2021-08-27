<?php

namespace App\Http\Resources;

use App\Models\Delivery;
use App\Models\DeliveryRequest;
use App\Models\NormalUser;
use App\Models\PromoCode;
use App\Models\Provider;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;
use phpDocumentor\Reflection\Types\Object_;

class OrderCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $data = [];
        foreach ($this as $obj) {
            $normal_user=NormalUser::where('user_id',$obj->user_id)->first();
            $provider=Provider::where('user_id',$obj->provider_id)->first();
            $arr['id'] = (int)$obj->id;
            $arr['status'] = $obj->status;
            $arr['user']['id'] = $obj->user_id;
            $arr['user']['name'] = $normal_user->name;
            $arr['provider']['id'] = $obj->provider_id;
            $arr['provider']['name'] = $provider->name;
            if ($obj->delivery_id==null){
                $arr['delivery']=new Object_();
            }else{
                $delivery_model=Delivery::where('user_id',$obj->delivery_id)->first();
                $delivery['id']=$obj->delivery_id;
                $delivery['name']=$delivery_model->name;
                $arr['delivery']=$delivery;
            }

            if ($obj['deliver_by']=='user') {
                $delivery_price=0;
            }elseif($obj['deliver_by']=='provider'){
                $delivery_price=$obj->orderItems->first()->cartItem->product->user->provider->delivery_price;
            }else{
                $delivery_price=0;
                if ($obj['delivery_id']!=null)
                {
                    $delivery_request=DeliveryRequest::where(['delivery_id'=>$obj['delivery_id'],'order_id'=>$obj->id,'status'=>'accepted'])->latest()->first();
                    if ($delivery_request){
                        $delivery_price=$delivery_request->delivery_price;
                    }
                }
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
