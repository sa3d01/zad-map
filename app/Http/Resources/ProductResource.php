<?php

namespace App\Http\Resources;

use App\Models\Cart;
use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    function inCart():bool
    {
        $in_cart=false;
        if (auth('api')->check()) {
            $pre_request_cart=Cart::where(['user_id'=>auth('api')->id(),'ordered'=>false])->latest()->first();
            if ($pre_request_cart){
                $cart_item=CartItem::where(['cart_id'=>$pre_request_cart->id,'product_id'=>$this['id']])->latest()->first();
                if ($cart_item){
                    $in_cart=true;
                }
            }
        }
        return $in_cart;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> (int)$this->id,
            'has_delivery'=> (int)$this->has_delivery,
            'name'=> $this->name,
            'note'=> $this->note,
            'category'=>[
                'id'=>$this->category->id,
                'name'=>$this->category->name,
            ],
            'provider'=>[
                'id'=>$this->user_id,
                'name'=>$this->user->name,
            ],
            'images'=>$this->images,
            'price'=> (double)$this->price,
            'in_cart'=>$this->inCart(),
        ];
    }
}
