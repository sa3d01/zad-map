<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Order\CancelOrderRequest;
use App\Http\Requests\Api\Order\PaymentOrderRequest;
use App\Models\CancelOrder;
use App\Models\Order;
use App\Models\OrderPay;

class OrderStatusController extends MasterController
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function cancelOrder($id, CancelOrderRequest $request): object
    {
        $request->validated();
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (($order->user_id != auth('api')->id() && $order->provider_id != auth('api')->id()) || ($order->status == 'rejected' || $order->status == 'completed')) {
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
