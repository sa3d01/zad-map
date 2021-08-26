<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;

class DeliveryController extends MasterController
{
    public function __construct(Delivery $model)
    {
        $this->model = $model;
//        $this->middleware('permission:deliveries');
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->where('approved', 1)->latest()->get();
        return view('Dashboard.delivery.index', compact('rows'));
    }

    public function binned()
    {
        $rows = $this->model->where('approved', 0)->latest()->get();
        return view('Dashboard.delivery.binned', compact('rows'));
    }

    public function show($id): object
    {
        $user = $this->model->find($id);
        return view('Dashboard.delivery.show', compact('user'));
    }

    public function reject($id, Request $request): object
    {
        $user = $this->model->find($id);
        $user->update(
            [
                'approved' => -1,
                'reject_reason' => $request['reject_reason'],
            ]
        );
        $user->refresh();
        $push = new PushNotification('fcm');
        $message = 'تم رفض انضمامك للسبب التالي :' . $request['reject_reason'];
        $usersTokens = [];
        if ($user->devices != null) {
            $usersTokens = $user->devices;
        }
        $push->setMessage([
            'notification' => array('title' => $message, 'sound' => 'default'),
            'data' => [
                'title' => $message,
                'body' => $message,
                'status' => 'admin',
                'type' => 'admin',
            ],
            'priority' => 'high',
        ])
            ->setDevicesToken($usersTokens)
            ->send()
            ->getFeedback();
        Notification::create([
            'receiver_id' => $id,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);
        $user->refresh();
        return redirect()->back()->with('updated');
    }

    public function accept($id)
    {
        $user = $this->model->find($id);
        $user->update(
            [
                'approved' => 1,
                'approved_at' => Carbon::now()
            ]
        );
        $user->refresh();
        $push = new PushNotification('fcm');
        $message = 'تم قبول انضمامك :)';
        $usersTokens = [];
        if ($user->devices != 'null') {
            $usersTokens = $user->devices;
        }

        $push->setMessage([
            'notification' => array('title' => $message, 'sound' => 'default'),
            'data' => [
                'title' => $message,
                'body' => $message,
                'status' => 'admin',
                'type' => 'admin',
            ],
            'priority' => 'high',
        ])
            ->setDevicesToken($usersTokens)
            ->send()
            ->getFeedback();
        Notification::create([
            'receiver_id' => $id,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);
        $user->refresh();
        return redirect()->back()->with('updated');
    }

}
