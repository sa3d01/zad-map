<?php

namespace App\Http\Resources;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductCollection extends ResourceCollection
{
    function inCart($product_id):array
    {
        $cart=[];
        $cart['in_cart']=false;
        $cart['cart_count']=0;
        if (auth('api')->check()) {
            $pre_request_cart=Cart::where(['user_id'=>auth('api')->id(),'ordered'=>false])->latest()->first();
            if ($pre_request_cart){
                $cart_item=CartItem::where(['cart_id'=>$pre_request_cart->id,'product_id'=>$product_id])->latest()->first();
                if ($cart_item){
                    $cart['in_cart']=true;
                    $cart['cart_count']=$cart_item->count;
                }
            }
        }
        return $cart;
    }

    public function toArray($request):array
    {
        $data = [];
        foreach ($this as $obj) {
            $arr['id'] = (int)$obj->id;
            $arr['has_delivery'] = (int)$obj->has_delivery;
            $arr['delivery_price'] = (int)$obj->delivery_price;
            $arr['name'] = $obj->name;
            $arr['image'] = $obj->images[0];
            $arr['price'] = (double)$obj->price;
            $arr['in_cart'] = $this->inCart($obj->id)['in_cart'];
            $arr['cart_count'] = $this->inCart($obj->id)['cart_count'];
            $data[] = $arr;
        }
        return $data;
    }
}
