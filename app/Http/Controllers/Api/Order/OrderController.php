<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Order\RateOrderRequest;
use App\Http\Requests\Api\Order\storeOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResourse;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Rate;
use App\Models\User;

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
        $status_arr=[$status,'delivered_to_delivery'];
        if ($user['type'] === 'USER') {
            if ($status=='in_progress'){
                $orders_q = Order::where('user_id' , auth('api')->id())->whereIn('status',$status_arr);
            }else{
                $orders_q = Order::where(['user_id' => auth('api')->id(), 'status' => $status]);
            }
        }elseif($user['type'] ==='DELIVERY') {
            if ($status=='in_progress'){
                $orders_q = Order::where('delivery_id' , auth('api')->id())->whereIn('status',$status_arr);
            }elseif ($status=='new'){
                $orders_q = Order::where(['deliver_by'=>'delivery','delivery_id'=>null,'status' => $status]);
            }else{
                $orders_q = Order::where(['delivery_id' => auth('api')->id(), 'status' => $status]);
            }
        }else {
            if ($status=='completed'){
                $orders_q = Order::where('provider_id' , auth('api')->id())->whereIn('status',$status_arr);
            }else{
                $orders_q = Order::where(['provider_id' => auth('api')->id(), 'status' => $status]);
            }
        }
        $orders = new OrderCollection($orders_q->latest()->get());
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
                $title = sprintf('يوجد لديك طلب جديد من قبل المستخدم %s , طلب رقم %s ',$order->user->name,$order->id);
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
        $title = sprintf('يوجد لديك طلب عرض توصيل من مستخدم %s , طلب رقم %s ',$user['name'],$order->id);
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

    public function rate($id,RateOrderRequest $request):object
    {
        $request->validated();
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (auth('api')->user()->type!='USER' || $order->status!='completed'){
            return $this->sendError("ﻻ يمكنك اجراء هذه العملية");
        }else{
            if ($request['provider']){
                Rate::Create([
                    'user_id'=>auth('api')->id(),
                    'order_id'=>$order->id,
                    'rated_id'=>$order->provider_id,
                    'rate'=>$request['provider']['rate'],
                    'feedback'=>$request['provider']['feedback'],
                ]);
                $title = sprintf('تم تقييمك من قبل المستخدم  %s , طلب رقم %s ',$order->user->name,$order->id);
                $this->notify_provider($order->provider,$title, $order);
            }
            if ($request['delivery']){
                Rate::Create([
                    'user_id'=>auth('api')->id(),
                    'order_id'=>$order->id,
                    'rated_id'=>$order->delivery_id,
                    'rate'=>$request['delivery']['rate'],
                    'feedback'=>$request['delivery']['feedback'],
                ]);
                $title = sprintf('تم تقييمك من قبل المستخدم  %s , طلب رقم %s ',$order->user->name,$order->id);
                $this->notify_provider($order->delivery,$title, $order);
            }
        }
        return $this->sendResponse([], 'تم التقييم بنجاح');
    }
}
