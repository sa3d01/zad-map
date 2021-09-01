<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Models\NormalUser;
use App\Models\Notification;
use App\Models\Provider;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;

class NotificationController extends MasterController
{
    public function __construct(Notification $model)
    {
        $this->model = $model;
//        $this->middleware('permission:notifications');
        parent::__construct();
    }

    public function clearAdminNotifications()
    {
        $unread_notifications = Notification::where(['type' => 'admin', 'read' => 'false'])->get();
        foreach ($unread_notifications as $unread_notification) {
            $unread_notification->update([
                'read' => 'true'
            ]);
        }
        return redirect()->back();
    }

    public function readNotification($id)
    {
        $unread_notification = Notification::find($id);
        $unread_notification->update([
            'read' => 'true'
        ]);
        return redirect()->back();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('Dashboard.notification.index', compact('rows'));
    }

    public function store(Request $request)
    {
        $data['title'] = 'رسالة إدارية';
        $data['note'] = $request['note'];
        foreach ($request['types'] as $type) {
            if ($type == 'USER') {
                $users = NormalUser::all();
            } elseif ($type == 'DELIVERY') {
                $users = Delivery::all();
            } else {
                $users = Provider::all();
            }
            $usersTokens = [];
            $usersIds = [];
            foreach ($users as $user) {
                if ($user->devices != null) {
                    $usersTokens[] = $user->devices;
                    $usersIds[] = $user->user_id;
                }
            }
            $push = new PushNotification('fcm');
            $push->setMessage([
                'notification' => array('title' => $data['note'], 'sound' => 'default'),
                'data' => [
                    'title' => $data['note'],
                    'body' => $data['note'],
                    'status' => 'admin',
                    'type' => 'admin',
                ],
                'priority' => 'high',
            ])
                ->setDevicesToken($usersTokens)
                ->send()
                ->getFeedback();
            $this->model->create([
                'receivers' => $usersIds,
                'receiver_type'=>$type,
                'admin_notify_type' => $type,
                'title' => $data['title'],
                'note' => $data['note'],
            ]);
        }
        return redirect()->back()->with('success', 'تم الارسال بنجاح');
    }

}
