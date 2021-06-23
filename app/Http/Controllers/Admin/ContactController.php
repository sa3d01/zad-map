<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Notification;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;

class ContactController extends MasterController
{
    public function __construct(Contact $model)
    {
        $this->model = $model;
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
            ->setDevicesToken((array)$contact->user->device['id'])
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
