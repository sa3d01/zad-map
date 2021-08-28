<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Delivery;
use App\Models\NormalUser;
use App\Models\Notification;
use App\Models\Provider;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;

class ContactController extends MasterController
{
    public function __construct(Contact $model)
    {
        $this->model = $model;
//        $this->middleware('permission:contacts');
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        foreach ($rows as $row) {
            $row->update([
                'read' => true
            ]);
        }
        return view('Dashboard.contact.index', compact('rows'));
    }

    public function replyContact($id, Request $request)
    {
        $data['title'] = 'رسالة إدارية';
        $data['note'] = $request['note'];
        $contact = Contact::find($id);

        if($contact->user_type=='USER'){
            $user_model=NormalUser::where('user_id',$contact->user_id)->first();
        }elseif($contact->user_type=='DELIVERY'){
            $user_model=Delivery::where('user_id',$contact->user_id)->first();
        }else{
            $user_model=Provider::where('user_id',$contact->user_id)->first();
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
            ->setDevicesToken($user_model->devices)
            ->send()
            ->getFeedback();
        Notification::create([
            'receiver_id' => $contact->user_id,
            'admin_notify_type' => 'single',
            'type' => 'app',
            'title' => $data['title'],
            'note' => $data['note'],
            'more_details' => [
                'type' => 'admin_reply',
                'contact_id' => $id
            ]
        ]);
        return redirect()->back()->with('success', 'تم الارسال بنجاح');
    }

    public function delete($id)
    {
        $this->model->find($id)->delete();
        return redirect()->back()->with('deleted', 'تم الحذف بنجاح');
    }
}
