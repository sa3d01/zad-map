<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\CartCollection;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends MasterController
{
    protected $model;

    public function __construct(Cart $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index(): object
    {
        $pre_request_cart = Cart::where(['user_id' => auth('api')->id(), 'ordered' => false])->latest()->first();
        if (!$pre_request_cart) {
            return $this->sendResponse([], 'السلة فارغة');
        } else {
            return $this->sendResponse(new CartCollection($pre_request_cart->cartItems));
        }
    }

    public function editCart($product_id, Request $request): object
    {
        $product=Product::find($product_id);
        if (!$product)
            return response()->json(['message' => "هذه القطعه غير متاحه."], 400);
        $user = $request->user();
        $pre_request_cart = Cart::where(['user_id' => $user->id, 'ordered' => false])->latest()->first();
        if (!$pre_request_cart) {
            $pre_request_cart = Cart::create([
                'user_id' => $user->id
            ]);
        }
        $cart_item = CartItem::where(['cart_id' => $pre_request_cart->id, 'product_id' => $product_id])->latest()->first();
        if ($cart_item) {
            $product->update([
                'count'=>$product->count+$cart_item->count
            ]);
            $cart_item->delete();
            return $this->sendResponse([], 'تم الحذف بنجاح');
        }
        $count=$request->input('count',1);
        CartItem::create(['cart_id' => $pre_request_cart->id, 'product_id' => $product_id,'count'=>$count]);
        $product->update([
            'count'=>$product->count-$count
        ]);
        return $this->sendResponse([], 'تمت الإضافة بنجاح');
    }

    public function addListToCart(Request $request): object
    {
        $user = $request->user();
        foreach (\request()->input('cart') as $obj){
            $product=Product::find($obj['product_id']);
            $product_count=$product->count;
            if (!$product)
                return response()->json(['message' => "هذه القطعه غير متاحه."], 400);
            $cart = Cart::where(['user_id' => $user->id, 'ordered' => false])->latest()->first();
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $user->id
                ]);
            }
            $cart_item = CartItem::where(['cart_id' => $cart->id, 'product_id' => $obj['product_id'],'count'=>$obj['count']])->latest()->first();
            if ($cart_item) {
                $product_count=$cart_item->count+$product_count;
                $cart_item->update([
                    'count'=>$obj['count']
                ]);
            }else{
                CartItem::create(['cart_id' => $cart->id, 'product_id' => $obj['product_id'],'count'=>$obj['count']]);
            }
            $product_count=$product_count-$obj['count'];
            $product->update([
               'count'=>$product_count
            ]);
        }
        return $this->sendResponse([], 'تمت الإضافة بنجاح');
    }

    public function updateCounts():object
    {
        foreach (\request()->input('cart') as $obj){
            $cartItem=CartItem::find($obj['cart_item_id']);
            if (!$cartItem){
                return $this->sendError("توجد مشكلة بالبيانات المرسلة.");
            }
            $product=Product::find($cartItem->product_id);
            $product_count=$product->count+$cartItem->count-$obj['count'];
            $cartItem->update([
                'count'=>$obj['count']
            ]);
            $product->update([
                'count'=>$product_count
            ]);
        }
        $pre_request_cart = Cart::where(['user_id' => auth('api')->id(), 'ordered' => false])->latest()->first();
        return $this->sendResponse(new CartCollection($pre_request_cart->cartItems));
    }
}
