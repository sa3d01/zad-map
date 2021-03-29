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
        if (!Product::find($product_id))
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
            $cart_item->delete();
            return $this->sendResponse([], 'تم الحذف بنجاح');
        }
        $count=$request->input('count',1);
        CartItem::create(['cart_id' => $pre_request_cart->id, 'product_id' => $product_id,'count'=>$count]);
        return $this->sendResponse([], 'تمت الإضافة بنجاح');
    }

    public function updateCounts():object
    {
        foreach (\request()->input('cart') as $obj){
            $cartItem=CartItem::find($obj['cart_item_id']);
            if (!$cartItem){
                return $this->sendError("توجد مشكلة بالبيانات المرسلة.");
            }
            $cartItem->update([
                'count'=>$obj['count']
            ]);
        }
        $pre_request_cart = Cart::where(['user_id' => auth('api')->id(), 'ordered' => false])->latest()->first();
        return $this->sendResponse(new CartCollection($pre_request_cart->cartItems));
    }
}
