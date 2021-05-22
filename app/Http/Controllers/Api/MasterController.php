<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResourse;
use App\Models\CancelOrder;
use App\Models\Notification;
use App\Models\Order;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Object_;

abstract class MasterController extends Controller
{
    protected $model;

    public function __construct()
    {
        $orders=Order::whereIn('status',['new','pre_paid','in_progress','delivered_to_delivery'])->get();
        foreach ($orders as $order){
            if (Carbon::parse($order->deliver_at)->format('Y-m-d') < Carbon::now()->format('Y-m-d')){
                $order->update([
                   'status'=>'rejected'
                ]);
                CancelOrder::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'reason' => 'انتهاء الوقت المحدد للتسليم'
                ]);
            }
        }
    }

    public function sendResponse($result, $message = null)
    {
        $response = [
            'status' => 200,
            'message' => $message ? $message : '',
            'data' => $result,
        ];
        return response()->json($response);
    }

    public function sendError($error,$data=[], $code = 400)
    {
        $response = [
            'status' => $code,
            'message' => $error,
            'data' => $data,
        ];
        return response()->json($response, $code);
    }

    function fcmPush($title,$user,$order)
    {
        if (in_array('id',$user->device)){
            $push = new PushNotification('fcm');
            $msg = [
                'notification' => array('title' => $title, 'sound' => 'default'),
                'data' => [
                    'title' => $title,
                    'body' => $title,
                    'status' => $order->status,
                    'type' => 'order',
                    'order' => new OrderResourse($order),
                ],
                'priority' => 'high',
            ];
            $push->setMessage($msg)
                ->setDevicesToken($user->device['id'])
                ->send();
        }
    }
    public function notify_provider($provider,$title, $order)
    {
        $this->fcmPush($title,$provider,$order);
        $notification['title'] = $title;
        $notification['note'] = $title;
        $notification['receiver_id'] = $provider->id;
        $notification['order_id'] = $order->id;
        Notification::create($notification);
    }
    public function notify_user($user,$title, $order)
    {
        $this->fcmPush($title,$user,$order);
        $notification['title'] = $title;
        $notification['note'] = $title;
        $notification['receiver_id'] = $user->id;
        $notification['order_id'] = $order->id;
        Notification::create($notification);
    }
}
