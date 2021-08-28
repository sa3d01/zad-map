<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Order\CancelOrderRequest;
use App\Http\Requests\Api\Order\PaymentOrderRequest;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResourse;
use App\Models\CancelOrder;
use App\Models\Delivery;
use App\Models\DeliveryRequest;
use App\Models\NormalUser;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderPay;
use App\Models\Provider;
use App\Models\Setting;
use App\Models\Wallet;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

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
        $order->update([
            'status'=>'completed'
        ]);
        $app_tax=Setting::value('app_tax');
        $provider_wallet=Wallet::where('user_id',$order->provider_id)->latest()->first();
        if (!$provider_wallet){
            Wallet::create([
               'user_id'=>$order->provider_id,
               'user_type'=>'PROVIDER',
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
            $delivery_price=DeliveryRequest::where(['order_id'=>$order->id,'delivery_id'=>$order->delivery_id,'status'=>'accepted'])->latest()->value('delivery_price');
            if (!$delivery_price){
                return $this->sendError('some thing error');
            }
            $delivery_wallet=Wallet::where('user_id',$order->delivery_id)->latest()->first();
            if (!$delivery_wallet){
                Wallet::create([
                    'user_id'=>$order->delivery_id,
                    'user_type'=>'DELIVERY',
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
        return true;
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
            $normal_user=NormalUser::where('user_id',$order->user_id)->first();

            $this->notify_user($normal_user,'تم تأكيد الاستلام من قبل المندوب  '.$normal_user->name, $order);
            return $this->sendResponse([], 'تم استلام الطلب بنجاح');
        }elseif (\request()->header('userType')=='USER'){
            if (($order->user_id != auth('api')->id()) || ($order->status == 'completed')) {
                return $this->sendError("تم تأكيد الاستلام من قبل");
            }
            $this->completeOrder($order);
            return $this->sendResponse([], 'تم استلام الطلب بنجاح');
        }else{
            return $this->sendError("ﻻ يمكنك تأكيد استلام هذا الطلب");
        }
    }

    public function deliverByUser($id): object
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if ( ($order->user_id != auth('api')->id() ) || ($order->deliver_by == 'delivery') ) {
            return $this->sendError("ﻻ يمكنك اجراء هذه العملية");
        }
        $order->update([
           'delivery_by'=>'user'
        ]);
        $normal_user=NormalUser::where('user_id',$order->user_id)->first();
        $provider_model=Provider::where('user_id',$order->provider_id)->first();
        $title = sprintf('سيتم استلام الطلب من قبل المستخدم  %s , طلب رقم %s ',$normal_user->name,$order->id);
        $this->notify_provider($provider_model,$title, $order);
        return $this->sendResponse([], 'تم تعديل الطلب بنجاح');
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
        $normal_user=NormalUser::where('user_id',$order->user_id)->first();
        $provider_model=Provider::where('user_id',$order->provider_id)->first();
        $delivery_model=Delivery::where('user_id',$order->delivery_id)->first();
        if (auth('api')->user()->type=='USER'){
            $title = sprintf('لقد تم الغاء الطلب من قبل المستخدم  %s , طلب رقم %s ',$normal_user->name,$order->id);
            $this->notify_provider($provider_model,$title, $order);
        }elseif (auth('api')->user()->type=='PROVIDER'){
            $title = sprintf('لقد تم الغاء الطلب من قبل مزود الخدمة  %s , طلب رقم %s ',$provider_model->name,$order->id);
            $this->notify_provider($normal_user,$title, $order);
        }else{
            $title = sprintf('لقد تم الغاء الطلب من قبل مندوب التوصيل  %s , طلب رقم %s ',$delivery_model->name,$order->id);
            $this->notify_provider($provider_model,$title, $order);
            $this->notify_provider($normal_user,$title, $order);
        }
        return $this->sendResponse([], 'تم الغاء الطلب بنجاح');
    }

    public function acceptOrder($id): object
    {
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if ($order->provider_id != auth('api')->id() || $order->status != 'new') {
            return $this->sendError("ﻻ يمكنك قبول هذا الطلب");
        }
        $order->update([
            'status'=>'pre_paid'
        ]);
        $normal_user=NormalUser::where('user_id',$order->user_id)->first();
        $this->notify_user($normal_user,'تمت الموافقة على طلبك من قبل '.$normal_user->name, $order);
        return $this->sendResponse([], 'تم قبول الطلب بنجاح');
    }

    public function payOrder($id,PaymentOrderRequest $request): object
    {
        $request->validated();
        $order = Order::find($id);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        if (\request()->header('userType')!='USER' || $order->status!='pre_paid'){
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
                $provider_model=Provider::where('user_id',$order->provider_id)->first();
                $this->notify_provider($provider_model,$title, $order);
            }
            if ($request['delivery'] && $order->delivery_id!=null){
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
                $delivery_model=Delivery::where('user_id',$order->delivery_id)->first();
                $this->notify_provider($delivery_model,$title, $order);
            }
            $order->update([
                'status'=>'in_progress'
            ]);
        }
        return $this->sendResponse([], 'تم تحديد أسلوب الدفع للطلب بنجاح');
    }

    public function acceptDeliveryRequest(Request $request):object
    {
        $order = Order::find($request['order_id']);
        if (!$order || $order->status!='new') {
            return $this->sendError("هذا الطلب غير موجود");
        }
        $delivery_request=DeliveryRequest::where(['order_id'=>$order->id,'delivery_id'=>auth('api')->id(),'status'=>'pending'])->latest()->first();
        if (!$delivery_request){
            return $this->sendError('some thing error');
        }
        $delivery_request->update([
            'delivery_price'=>$request['delivery_price'],
            'status'=>'accepted'
        ]);
        $delivery_model=Delivery::where('user_id',auth('api')->id())->first();
        $title = sprintf('يوجد لديك عرض سعر جديد من قبل %s , طلب رقم %s ',$delivery_model->name,$order->id);
        $notification['title'] = $title;
        $notification['note'] = $title;
        $notification['receiver_id'] = $order->user_id;
        $notification['order_id'] = $order->id;
        Notification::create($notification);
        $normal_user=NormalUser::where('user_id',$order->user_id)->first();
        if ($normal_user->devices != null){
            $push = new PushNotification('fcm');
            $msg = [
                'notification' => array('title' => $title, 'sound' => 'default'),
                'data' => [
                    'title' => $title,
                    'body' => $title,
                    'status' => $order->status,
                    'type' => 'delivery_request',
                    'order' => new OrderResourse($order),
                ],
                'priority' => 'high',
            ];
            $push->setMessage($msg)
                ->setDevicesToken($normal_user->devices)
                ->send();
        }
        $orders_q = Order::where('delivery_id' , auth('api')->id())->where('status','new');
        $orders = new OrderCollection($orders_q->latest()->get());
        return $this->sendResponse($orders);
    }

    public function rejectDeliveryRequest(Request $request):object
    {
        $order = Order::find($request['order_id']);
        if (!$order) {
            return $this->sendError("هذا الطلب غير موجود");
        }
        $delivery_request=DeliveryRequest::where(['order_id'=>$order->id,'delivery_id'=>auth('api')->id(),'status'=>'pending'])->latest()->first();
        if (!$delivery_request){
            return $this->sendError('some thing error');
        }
        $delivery_request->update([
            'status'=>'rejected'
        ]);
        $orders_q = Order::where('delivery_id' , auth('api')->id())->where('status','new');
        $orders = new OrderCollection($orders_q->latest()->get());
        return $this->sendResponse($orders);
    }

    public function acceptDelivery($order_id,$delivery_request_id):object
    {
        $order=Order::find($order_id);
        $delivery_request = DeliveryRequest::find($delivery_request_id);
        if (!$delivery_request || $order->status!='new') {
            return $this->sendError("هذا الطلب غير موجود");
        }
        $order->update([
           'delivery_id'=>$delivery_request->delivery_id
        ]);
        $other_requests=DeliveryRequest::where('order_id',$order->id)->where('delivery_id','!=',$delivery_request->delivery_id)->get();
        foreach ($other_requests as $other_request){
            $other_request->update([
                'status'=>'rejected'
            ]);
        }
        $notifications=Notification::where('type','delivery_request')->where('order_id',$order->id)->get();
        foreach ($notifications as $notification){
            $notification->delete();
        }
        $title = sprintf('تم قبول عرض سعرك من قبل %s , طلب رقم %s ',auth('api')->user()->name,$order_id);
        $delivery_model=Delivery::where('user_id',$order->delivery_id)->first();
        $provider_model=Provider::where('user_id',$order->provider_id)->first();
        $this->notify_user($delivery_model,$title, $order);
        $title = sprintf('يوجد طلب جديد من قبل %s , طلب رقم %s ',auth('api')->user()->name,$order_id);
        $this->notify_provider($provider_model,$title, $order);
        $orders_q = Order::where('user_id' , auth('api')->id())->where('status','new');
        $orders = new OrderCollection($orders_q->latest()->get());
        return $this->sendResponse($orders);
    }
}
