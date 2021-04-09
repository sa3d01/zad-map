<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResourse;
use App\Models\Notification;
use Edujugon\PushNotification\PushNotification;
use phpDocumentor\Reflection\Types\Object_;

abstract class MasterController extends Controller
{
    protected $model;

    public function __construct()
    {
    }

    public function sendResponse($result, $message = null)
    {
        try {
            if (count($result)==0){
                $result=new Object_();
            }
        }catch (\Exception $e){

        }
        $response = [
            'status' => 200,
            'message' => $message ? $message : '',
            'data' => $result,
        ];
        return response()->json($response);
    }

    public function sendError($error,$data=[], $code = 400)
    {
        try {
            if (count($data)==0){
                $data=new Object_();
            }
        }catch (\Exception $e){

        }
        $response = [
            'status' => $code,
            'message' => $error,
            'data' => $data,
        ];
        return response()->json($response, $code);
    }

    function fcmPush($title,$user,$order)
    {
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
