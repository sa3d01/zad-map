<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Order\CancelOrderRequest;
use App\Http\Requests\Api\Order\PaymentOrderRequest;
use App\Models\CancelOrder;
use App\Models\Order;
use App\Models\OrderPay;
use App\Models\Setting;
use App\Models\Wallet;

class OrderStatusController extends MasterController
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    function completeOrder($order)
    {
        $app_tax=Setting::value('app_tax');
        $order->update([
            'status'=>'completed'
        ]);
        $provider_wallet=Wallet::where('user_id',$order->provider_id)->latest()->first();
        if (!$provider_wallet){
            Wallet::create([
               'user_id'=>$order->provider_id,
               'profits'=>$order->price(),
               'debtors'=>($order->price()*$app_tax)/100
            ]);
        }else{
            $provider_wallet->update([
                'profits'=>$provider_wallet->profits+$order->price(),
                'debtors'=>($provider_wallet->debtors)+(($order->price()*$app_tax)/100)
            ]);
        }
        if ($order->delivery_id!=null)
        {
            $delivery_price=Setting::value('delivery_price');
            $delivery_wallet=Wallet::where('user_id',$order->delivery_id)->latest()->first();
            if (!$delivery_wallet){
                Wallet::create([
                    'user_id'=>$order->delivery_id,
                    'profits'=>$delivery_price,
                    'debtors'=>($delivery_price*$app_tax)/100
                ]);
            }else{
                $delivery_wallet->update([
                    'profits'=>$delivery_wallet->profits+$delivery_price,
                    'debtors'=>($delivery_wallet->debtors)+(($delivery_price*$app_tax)/100)
                ]);
            }
        }
    }

    public function delivered($id):object
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (auth('api')->user()->type=='DELIVERY'){
            if (($order->delivery_id != auth('api')->id()) || $order->status != 'in_progress') {
                return $this->sendError("ﻻ يمكنك تأكيد استلام هذا الطلب");
            }
            $order->update([
                'status'=>'delivered_to_delivery'
            ]);
            $this->notify_user($order->user,'تم تأكيد الاستلام من قبل المندوب  '.auth('api')->user()->name, $order);
            return $this->sendResponse([], 'تم استلام الطلب بنجاح');
        }elseif (auth('api')->user()->type=='USER'){
            if (($order->user_id != auth('api')->id()) || ($order->status == 'completed')) {
                return $this->sendError("تم تأكيد الاستلام من قبل");
            }
            $this->completeOrder($order);
            return $this->sendResponse([], 'تم استلام الطلب بنجاح');
        }else{
            return $this->sendError("ﻻ يمكنك تأكيد استلام هذا الطلب");
        }
    }
    public function cancelOrder($id, CancelOrderRequest $request): object
    {
        $request->validated();
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (($order->user_id != auth('api')->id() && $order->provider_id != auth('api')->id() && $order->delivery_id != auth('api')->id()) || ($order->status == 'rejected' || $order->status == 'completed')) {
            return $this->sendError("ﻻ يمكنك الغاء هذا الطلب");
        }
        $order->update([
           'status'=>'rejected'
        ]);
        CancelOrder::create([
            'user_id' => auth('api')->id(),
            'order_id' => $id,
            'reason' => $request['reason']
        ]);
        //todo:notify
        if (auth('api')->user()->type=='USER'){
            $title = sprintf('لقد تم الغاء الطلب من قبل المستخدم  %s , طلب رقم %s ',$order->user->name,$order->id);
            $this->notify_provider($order->provider,$title, $order);
        }elseif (auth('api')->user()->type=='PROVIDER'){
            $title = sprintf('لقد تم الغاء الطلب من قبل مزود الخدمة  %s , طلب رقم %s ',$order->provider->name,$order->id);
            $this->notify_provider($order->user,$title, $order);
        }else{
            $title = sprintf('لقد تم الغاء الطلب من قبل مندوب التوصيل  %s , طلب رقم %s ',$order->delivery->name,$order->id);
            $this->notify_provider($order->provider,$title, $order);
            $this->notify_provider($order->user,$title, $order);
        }
        return $this->sendResponse([], 'تم الغاء الطلب بنجاح');
    }
    public function acceptOrder($id): object
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (auth('api')->user()->type=='DELIVERY'){
            if ($order->delivery_id != null || $order->deliver_by != 'delivery') {
                return $this->sendError("ﻻ يمكنك قبول هذا الطلب");
            }
            $title = sprintf('يوجد لديك طلب جديد من قبل المستخدم %s , طلب رقم %s ',$order->user->name,$order->id);
            $this->notify_provider($order->provider,$title, $order);
            $order->update([
               'delivery_id'=>auth('api')->id()
            ]);
            $this->notify_user($order->user,'تمت الموافقة على طلب التوصيل من قبل '.auth('api')->user()->name, $order);
        }else{
            if ($order->provider_id != auth('api')->id() || $order->status != 'new') {
                return $this->sendError("ﻻ يمكنك قبول هذا الطلب");
            }
            $order->update([
                'status'=>'pre_paid'
            ]);
            $this->notify_user($order->user,'تمت الموافقة على طلبك من قبل '.auth('api')->user()->name, $order);
        }
        return $this->sendResponse([], 'تم قبول الطلب بنجاح');
    }
    public function payOrder($id,PaymentOrderRequest $request): object
    {
        $request->validated();
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (auth('api')->user()->type!='USER' || $order->status!='pre_paid'){
            return $this->sendError("ﻻ يمكنك اجراء هذه العملية");
        }else{
            if ($request['provider']){
                if (array_key_exists("image",$request['provider']))
                {
                    $image=$request['provider']['image'];
                }else{
                    $image=null;
                }
                OrderPay::Create([
                    'user_id'=>auth('api')->id(),
                    'order_id'=>$order->id,
                    'provider_id'=>$order->provider_id,
                    'type'=>$request['provider']['type'],
                    'image'=>$image,
                ]);
                $title = sprintf('تم تحديد أسلوب الدفع من قبل المستخدم  %s , طلب رقم %s ',$order->user->name,$order->id);
                $this->notify_provider($order->provider,$title, $order);
            }
            if ($request['delivery']){
                if (array_key_exists("image",$request['delivery']))
                {
                    $image=$request['delivery']['image'];
                }else{
                    $image=null;
                }
                OrderPay::Create([
                    'user_id'=>auth('api')->id(),
                    'order_id'=>$order->id,
                    'delivery_id'=>$order->delivery_id,
                    'type'=>$request['delivery']['type'],
                    'image'=> $image,
                ]);
                $title = sprintf('تم تحديد أسلوب الدفع من قبل المستخدم  %s , طلب رقم %s ',$order->user->name,$order->id);
                $this->notify_provider($order->delivery,$title, $order);
            }
            $order->update([
                'status'=>'in_progress'
            ]);
        }
        return $this->sendResponse([], 'تم تحديد أسلوب الدفع للطلب بنجاح');
    }
}
