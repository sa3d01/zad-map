<?php

namespace App\Http\Resources;

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
            $delivery['location']=$delivery_model->location;
            $delivery['phone']=$delivery_model->phone;
            $delivery['rating']=4;
        }
        if ($this['deliver_by']=='delivery')
        {
            $delivery_price=Setting::value('delivery_price');
        }else{
            $delivery_price=$this->orderItems->first()->cartItem->product->delivery_price;
        }
        return [
            'id' => (int)$this['id'],
            'user' => [
                'id' => $this['user_id'],
                'name' => $this->user->name,
                'phone' => $this->user->phone,
                'location' => $this->user->location,
            ],
            'provider' => [
                'id' => $this['provider_id'],
                'name' => $this->provider->name,
                'location' => $this->provider->location,
                'phone' => $this->provider->phone,
                'rating' => 3.5,
            ],
            'delivery' => $delivery,
            'deliver_by' => $this->deliver_by,
            'deliver_at' => $this->deliver_at,
            'status' => $this->status,
            'products'=>new OrderItemCollection($this->orderItems),
            'price' => $this->price(),
            'delivery_price' => $delivery_price,
            'total_price' => $this->price()+($delivery_price),
            'cancel_reason'=>$this->cancelReason()
        ];
    }
}
