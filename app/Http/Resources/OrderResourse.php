<?php

namespace App\Http\Resources;

use App\Models\OrderPay;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

class OrderResourse extends JsonResource
{
    public function toArray($request)
    {
        if ($this['delivery_id']==null){
            $delivery=new Object_();
        }else{
            $delivery_model=User::find($this['delivery_id']);
            $delivery['id']=$delivery_model->id;
            $delivery['name']=$delivery_model->name;
            $delivery['image']=$delivery_model->image;
            $delivery['location']=$delivery_model->location;
            $delivery['phone']=$delivery_model->phone;
            $delivery['rating']=(double)$delivery_model->averageRate();
        }
        if ($this['deliver_by']=='delivery')
        {
            $delivery_price=Setting::value('delivery_price');
        }else{
            $delivery_price=$this->orderItems->first()->cartItem->product->delivery_price;
        }

        $delivery_payment=OrderPay::where(['order_id'=>$this['id'],'delivery_id'=>$this['delivery_id']])->latest()->first();
        $delivery_payment_model['type']=$delivery_payment->type??"";
        $delivery_payment_model['image']=$delivery_payment->image??"";
        $payment_model['delivery']=$delivery_payment_model;

        $provider_payment=OrderPay::where(['order_id'=>$this['id'],'provider_id'=>$this['provider_id']])->latest()->first();
        $provider_payment_model['type']=$provider_payment->type??"";
        $provider_payment_model['image']=$provider_payment->image??"";
        $payment_model['provider']=$provider_payment_model;

        return [
            'id' => (int)$this['id'],
            'user' => [
                'id' => $this['user_id'],
                'name' => $this->user->name,
                'image' => $this->user->image,
                'phone' => $this->user->phone,
                'location' => $this->user->location,
            ],
            'provider' => [
                'id' => $this['provider_id'],
                'name' => $this->provider->name,
                'image' => $this->provider->image,
                'location' => $this->provider->location,
                'phone' => $this->provider->phone,
                'rating' => (double)$this->provider->averageRate(),
            ],
            'delivery' => $delivery,
            'deliver_by' => $this->deliver_by,
            'deliver_at' => $this->deliver_at,
            'status' => $this->status,
            'products'=>new OrderItemCollection($this->orderItems),
            'price' => $this->price(),
            'delivery_price' => $delivery_price,
            'total_price' => $this->price()+($delivery_price),
            'cancel_reason'=>$this->cancelReason(),
            'payment'=>$payment_model
        ];
    }
}
