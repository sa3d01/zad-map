<?php

namespace App\Http\Resources;

use App\Models\CartItem;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderItemCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $data = [];
        foreach ($this as $obj) {
            $cart_item=CartItem::find($obj['cart_item_id']);
            $arr['id'] = (int)$cart_item->product_id;
            $arr['cart_item_id'] = (int)$obj['cart_item_id'];
            $arr['count'] = $cart_item->count;
            $arr['name'] = $cart_item->product->name;
            $arr['image'] = $cart_item->product->images[0];
            $arr['price'] = (double)$cart_item->count*$cart_item->product->price;
            $data[] = $arr;
        }
        return $data;
    }
}
