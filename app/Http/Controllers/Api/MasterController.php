<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResourse;
use App\Models\CancelOrder;
use App\Models\Car;
use App\Models\Delivery;
use App\Models\NormalUser;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Setting;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;

abstract class MasterController extends Controller
{
    protected $model;

    public function __construct()
    {
        $orders = Order::whereIn('status', ['new', 'pre_paid', 'in_progress', 'delivered_to_delivery'])->get();
        foreach ($orders as $order) {
            if (Carbon::parse($order->deliver_at)->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
                $order->update([
                    'status' => 'rejected'
                ]);
                CancelOrder::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'reason' => 'انتهاء الوقت المحدد للتسليم'
                ]);
            }
        }
        $notify_paid_period = (int)Setting::value('notify_paid_period');
        $orders = Order::where('status', 'pre_paid')->get();
        foreach ($orders as $order) {
            if (Carbon::now()->gt(Carbon::parse($order->updated_at)->addMinutes($notify_paid_period))) {
                $title = 'يرجي دفع المستحقات المعلقة بالطلب رقم #' . $order->id;
                $normal_user = NormalUser::where('user_id', $order->user_id)->first();
                $last_notify = Notification::where(['receiver_id' => $order->user_id, 'type' => 'order'])->where('title', $title)->latest()->first();
                if ($last_notify) {
                    if (Carbon::now()->diffInMinutes(Carbon::parse($last_notify->created_at)) > 15) {
                        $this->notify_user($normal_user, $title, $order);
                        $order->update();
                    }
                } else {
                    $order->update();
                    $this->notify_user($normal_user, $title, $order);
                }
            }
        }

        $period_to_delivery_approved = (int)Setting::value('period_to_delivery_approved');
        $orders = Order::where(['deliver_by' => 'delivery', 'delivery_id' => null])->get();
        foreach ($orders as $order) {
            if (Carbon::now()->gt(Carbon::parse($order->created_at)->addMinutes($period_to_delivery_approved))) {
                $title = 'لا يوجد مندوبين حاليا لتوصيل طلبك #' . $order->id;
                $normal_user = NormalUser::where('user_id', $order->user_id)->first();
                $last_notify = Notification::where(['receiver_id' => $order->user_id, 'type' => 'order'])->where('title', $title)->latest()->first();
                if ($last_notify) {
                    if (Carbon::now()->diffInMinutes(Carbon::parse($last_notify->created_at)) > 15) {
                        $this->notify_user($normal_user, $title, $order);
                    }
                } else {
                    $order->update([
                        'delivery_approved_expired' => true
                    ]);
                    $this->notify_user($normal_user, $title, $order);
                }
            }
        }

        $cars = Car::all();
        foreach ($cars as $car) {
            $delivery = Delivery::where('user_id', $car->user_id)->first();
            if (Carbon::parse($car->end_insurance_date)->format('Y-m-d') > Carbon::now()->format('Y-m-d')) {
                //expired
                $title = 'لقد انتهي ميعاد التأمين الخاص بسيارتك ';
            } elseif (Carbon::now()->diffInDays(Carbon::parse($car->end_insurance_date)) < 15) {
                //soon
                $title = 'لقد قارب ميعاد انتهاء التأمين الخاص بسيارتك ';
            }
            $last_notify = Notification::where(['receiver_id' => $car->user_id, 'type' => 'end_insurance_date'])->latest()->first();
            if ($last_notify) {
                if (Carbon::now()->diffInDays(Carbon::parse($last_notify->created_at)) > 0) {
                    if ($delivery->devices != null) {
                        $push = new PushNotification('fcm');
                        $msg = [
                            'notification' => array('title' => $title, 'sound' => 'default'),
                            'data' => [
                                'title' => $title,
                                'body' => $title,
                                'status' => 'end_insurance_date',
                                'type' => 'app',
                            ],
                            'priority' => 'high',
                        ];
                        $push->setMessage($msg)
                            ->setDevicesToken($delivery->devices)
                            ->send();
                    }
                    $notification['type'] = 'end_insurance_date';
                    $notification['title'] = $title;
                    $notification['note'] = $title;
                    $notification['receiver_id'] = $car->user_id;
                    Notification::create($notification);
                }
            } else {
                if ($delivery->devices != null) {
                    $push = new PushNotification('fcm');
                    $msg = [
                        'notification' => array('title' => $title, 'sound' => 'default'),
                        'data' => [
                            'title' => $title,
                            'body' => $title,
                            'status' => 'end_insurance_date',
                            'type' => 'app',
                        ],
                        'priority' => 'high',
                    ];
                    $push->setMessage($msg)
                        ->setDevicesToken($delivery->devices)
                        ->send();
                }
                $notification['type'] = 'end_insurance_date';
                $notification['title'] = $title;
                $notification['note'] = $title;
                $notification['receiver_id'] = $car->user_id;
                Notification::create($notification);
            }

        }


    }

    public function sendResponse($result, $message = null)
    {
        $response = [
            'status' => 200,
            'message' => $message ?? '',
            'data' => $result,
        ];
        return response()->json($response);
    }

    public function sendError($error, $data = [], $code = 400)
    {
        $response = [
            'status' => $code,
            'message' => $error,
            'data' => $data,
        ];
        return response()->json($response, $code);
    }

    function fcmPush($title, $user, $order)
    {
        if ($user->devices != null) {
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
                ->setDevicesToken($user->devices)
                ->send();
        }
    }

    public function notify_provider($provider, $title, $order)
    {
        $this->fcmPush($title, $provider, $order);
        $notification['type'] = 'order';
        $notification['title'] = $title;
        $notification['note'] = $title;
        $notification['receiver_id'] = $provider->user_id;
        $notification['order_id'] = $order->id;
        Notification::create($notification);
    }

    public function notify_user($user, $title, $order)
    {
        $this->fcmPush($title, $user, $order);
        $notification['type'] = 'order';
        $notification['title'] = $title;
        $notification['note'] = $title;
        $notification['receiver_id'] = $user->user_id;
        $notification['order_id'] = $order->id;
        Notification::create($notification);
    }
}
