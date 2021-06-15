<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Notification;
use App\Models\User;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;

class NotificationController extends MasterController
{
    public function __construct(Notification $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function clearAdminNotifications()
    {
        $unread_notifications=Notification::where(['type'=>'admin','read'=>'false'])->get();
        foreach ($unread_notifications as $unread_notification){
            $unread_notification->update([
               'read'=>'true'
            ]);
        }
        return redirect()->back();
    }
    public function readNotification($id)
    {
        $unread_notification=Notification::find($id);
        $unread_notification->update([
            'read'=>'true'
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
        $data['title']='رسالة إدارية';
        $data['note']=$request['note'];
        foreach ($request['types'] as $type){
            $users=User::where('type',$type)->get();
            $usersTokens=[];
            $usersIds=[];
            foreach ($users as $user){
                if ($user->device['id'] !='null'){
                    $usersTokens[]=$user->device['id'];
                    $usersIds[]=$user->id;
                }
            }
            $push = new PushNotification('fcm');
            $feed=$push->setMessage([
                'notification' => array('title'=>$data['note'], 'sound' => 'default'),
                'data' => [
                    'title' => $data['note'],
                    'body' => $data['note'],
                    'status' => 'admin',
                    'type'=>'admin',
                ],
                'priority' => 'high',
            ])
                ->setDevicesToken($usersTokens)
                ->send()
                ->getFeedback();
            $this->model->create([
                'receivers'=>$usersIds,
                'admin_notify_type'=>$type,
                'title'=>$data['title'],
                'note'=>$data['note'],
            ]);
        }
        return redirect()->back()->with('success','تم الارسال بنجاح');
    }

}
