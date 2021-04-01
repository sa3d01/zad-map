<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Order\storeOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResourse;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Edujugon\PushNotification\PushNotification;

class OrderController extends MasterController
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function filteredOrders($status): object
    {
        $user = auth('api')->user();
        if ($user['type'] === 'USER') {
            $orders = new OrderCollection(Order::where(['user_id' => $user->id, 'status' => $status])->latest()->get());
        } elseif($user['type'] ==='DELIVERY') {
            if ($status=='new'){
                $orders = new OrderCollection(Order::where(['deliver_by'=>'delivery','delivery_id'=>null,'status' => $status])->latest()->get());
            }else{
                $orders = new OrderCollection(Order::where(['delivery_id' => $user->id, 'status' => $status])->latest()->get());
            }
        }else {
            $orders = new OrderCollection(Order::where(['provider_id' => $user->id, 'status' => $status])->latest()->get());
        }
        return $this->sendResponse($orders);
    }

    public function show($id): object
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        return $this->sendResponse(new OrderResourse($order));
    }

    public function update($id): object
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if ($order->user_id != auth('api')->id() || $order->status != 'new') {
            return $this->sendError("ﻻ يمكنك تعديل هذا الطلب");
        }
        foreach (\request()->input('cart') as $obj) {
            $cartItem = CartItem::find($obj['cart_item_id']);
            if (!$cartItem) {
                return $this->sendError("توجد مشكلة بالبيانات المرسلة.");
            }
            $cartItem->update([
                'count' => $obj['count']
            ]);
            $old_order_items = OrderItem::where([
                'order_id' => $order->id,
                'cart_item_id' => $cartItem->id,
            ])->get();
            foreach ($old_order_items as $old_order_item) {
                $old_order_item->delete();
            }
            OrderItem::create([
                'order_id' => $order->id,
                'cart_item_id' => $cartItem->id,
            ]);
        }
        return $this->sendResponse(new OrderResourse($order));
    }

    public function store(storeOrderRequest $request): object
    {
        $data = $request->validated();
        $data['user_id'] = auth('api')->id();
        //all cart items
        $cart = Cart::where(['user_id' => auth('api')->id(), 'ordered' => 0])->latest()->first();
        if (!$cart) {
            return $this->sendError("سلتك فارغة");
        }
        $cart_items = $cart->cartItems;
        $provider_arr = [];
        foreach ($cart_items as $cart_item) {
            $provider_arr[] = $cart_item->product->user_id;
        }
        $provider_arr = array_unique($provider_arr);
        //loop in providers to create multi orders
        foreach ($provider_arr as $key => $provider_id) {
            $data['provider_id'] = $provider_id;
            $order = Order::create($data);
            //cart items of specific provider
            foreach ($cart_items as $cart_item) {
                if ($cart_item->product->user_id != $provider_id) {
                    continue;
                }
                OrderItem::create([
                    'order_id' => $order->id,
                    'cart_item_id' => $cart_item->id,
                ]);
            }
            //check deliver by
            if ($order->deliver_by!='delivery'){
                $title = 'لديك طلب جديد عن طريق ' . $order->user->name;
                $this->notify_provider(User::find($provider_id),$title, $order);
            }else{
                $this->notify_deliveries($order);
            }
        }
        $cart->update([
            'ordered' => 1
        ]);
        return $this->sendResponse([], " تم الارسال بنجاح .. يرجى انتظار موافقة مزود الخدمة");
    }

    public function notify_deliveries($order)
    {
        $user = auth('api')->user();
        $title = 'لديك طلب توصيل جديد عن طريق ' . $user['name'];
        $deliveries=User::whereType('DELIVERY')->where('online',1)->where('device','!=',null)->get();
        foreach ($deliveries as $delivery){
            $this->fcmPush($title,$delivery,$order);
            $notification['title'] = $title;
            $notification['note'] = $title;
            $notification['receiver_id'] = $delivery->id;
            $notification['order_id'] = $order->id;
            Notification::create($notification);
        }
    }

}
