<?php

namespace App\Http\Resources;

use App\Models\Chat;
use App\Models\Delivery;
use App\Models\OrderPay;
use App\Models\PromoCode;
use App\Models\Rate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

class OrderResourse extends JsonResource
{
    function getCityData($user)
    {
         return [
             'id' => $user->city ? (int)$user->city->id : 0,
             'name' => $user->city ? $user->city->name : "",
         ];
    }
    function getDistrictData($user)
    {
         return [
             'id' => $user->district ? (int)$user->district->id : 0,
             'name' => $user->district ? $user->district->name : "",
         ];
    }
    public function toArray($request)
    {
        if ($this['delivery_id']==null){
            $delivery=new Object_();
        }else{
            $delivery_model=Delivery::where('user_id',$this['delivery_id'])->first();
            $provider_chat = Chat::where('order_id',$this['id'])->latest()->first();
            $delivery['id']=$delivery_model->user_id;
            $delivery['name']=$delivery_model->name;
            $delivery['image']=$delivery_model->image;
            $delivery['location']=$delivery_model->location;
            $delivery['city']=$this->getCityData($delivery_model);
            $delivery['district']=$this->getDistrictData($delivery_model);
            $delivery['phone']=$delivery_model->user->phone;
            $delivery['rating']=(double)$delivery_model->user->averageRate();
            $delivery['room'] = $provider_chat?$provider_chat->room:(int)($this['id'].$delivery_model->user_id);

            $delivery_price=$this->orderItems->first()->cartItem->product->user->provider->delivery_price;

        }
        if ($this['deliver_by']=='user')
        {
            $delivery_price=0;
        }elseif($this['deliver_by']=='provider'){
            $delivery_price=$this->orderItems->first()->cartItem->product->user->provider->delivery_price;
        }
        $delivery_payment=OrderPay::where(['order_id'=>$this['id'],'delivery_id'=>$this['delivery_id']])->latest()->first();
        $delivery_payment_model['type']=$delivery_payment->type??"";
        $delivery_payment_model['image']=$delivery_payment->image??"";
        $payment_model['delivery']=$delivery_payment_model;
        $provider_payment=OrderPay::where(['order_id'=>$this['id'],'provider_id'=>$this['provider_id']])->latest()->first();
        $provider_payment_model['type']=$provider_payment->type??"";
        $provider_payment_model['image']=$provider_payment->image??"";
        $payment_model['provider']=$provider_payment_model;

        $provider_rating_model=Rate::where(['order_id'=>$this['id'],'rated_id'=>$this['provider_id']])->latest()->first();
        $delivery_rating_model=Rate::where(['order_id'=>$this['id'],'rated_id'=>$this['delivery_id']])->latest()->first();
        if ($provider_rating_model){
            $provider_rating['rate']=$provider_rating_model->rate;
            $provider_rating['feedback']=$provider_rating_model->feedback;
        }else{
            $provider_rating=new Object_();
        }
        if ($delivery_rating_model){
            $delivery_rating['rate']=$delivery_rating_model->rate;
            $delivery_rating['feedback']=$delivery_rating_model->feedback;
        }else{
            $delivery_rating=new Object_();
        }

        $promo_code = PromoCode::where('code', $this->promo_code)->first();
        $discount=0;
        if ($promo_code){
            $discount=$promo_code->discount_percent*($this->price()+($delivery_price))/100;
        }

        $provider_chat = Chat::where('order_id',$this['id'])->latest()->first();
        $can_confirm=false;

        if (request()->header('userType')=='USER')
        {
            if ($this['delivery_id']!=null){
                if ($this['status']=='delivered_to_delivery'){
                    $can_confirm=true;
                }
            }else{
                if ($this['status']=='in_progress'){
                    $can_confirm=true;
                }
            }
        }elseif (request()->header('userType')=='DELIVERY')
        {
            if ($this['status']=='in_progress'){
                $can_confirm=true;
            }
        }
        return [
            'id' => (int)$this['id'],
            'user' => [
                'id' => $this['user_id'],
                'name' => $this->user->normal_user->name,
                'image' => $this->user->normal_user->image,
                'phone' => $this->user->phone,
                'location' => $this->user->normal_user->location,
                'city'=>$this->getCityData($this->user->normal_user),
                'district'=>$this->getDistrictData($this->user->normal_user)
            ],
            'provider' => [
                'id' => $this['provider_id'],
                'name' => $this->provider->provider->name,
                'image' => $this->provider->provider->image,
                'location' => $this->provider->provider->location,
                'city'=>$this->getCityData($this->provider->provider),
                'district'=>$this->getDistrictData($this->provider->provider),
                'phone' => $this->provider->phone,
                'rating' => (double)$this->provider->averageRate(),
                'room' => $provider_chat?$provider_chat->room:(int)($this['id'].$this['provider_id']),
            ],
            'delivery' => $delivery,
            'deliver_by' => $this->deliver_by,
            'deliver_at' => $this->deliver_at,
            'address' => $this->address??"",
            'status' => $this->status,
            'products'=>new OrderItemCollection($this->orderItems),
            'price' => $this->price(),
            'delivery_price' => $delivery_price,
            'discount' => $discount,
            'total_price' => ($this->price()+($delivery_price))-$discount,
            'cancel_reason'=>$this->cancelReason(),
            'payment'=>$payment_model,
            'provider_rating'=>$provider_rating,
            'delivery_rating'=>$delivery_rating,
            'can_confirm'=>$can_confirm
        ];
    }
}
