<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CartCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $data = [];
        $subtotal=0;
        foreach ($this as $obj) {
            $product = Product::find($obj['product_id']);
            $arr['id'] = (int)$product->id;
            $arr['cart_item_id'] = (int)$obj->id;
            $arr['count'] = (int)$obj->count;
            $arr['provider']['id'] = $product->user_id;
            $arr['provider']['name'] = $product->user->name;
            $arr['name'] = $product->name;
            $arr['image'] = $product->images[0];
            $arr['price'] = (double)$product->price;
            $arr['delivery_price'] = (double)$product->delivery_price;
            $arr['has_delivery'] = (double)$product->has_delivery;
            $arr['in_cart'] = true;
            $subtotal+=$product->price*$obj->count;
            $data['products'][] = $arr;
            $data['total_price'] =(double)$subtotal;
        }
        return $data;
    }
}
