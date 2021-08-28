<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Notification;
use App\Models\Provider;
use App\Models\User;
use App\Traits\UserBanksAndCarsTrait;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;

class ProviderController extends MasterController
{
    use UserBanksAndCarsTrait;
    public function __construct(Provider $model)
    {
        $this->model = $model;
//        $this->middleware('permission:providers');
        parent::__construct();
    }

    public function index()
    {
        $rows = Provider::where('approved', 1)->latest()->get();
        return view('Dashboard.provider.index', compact('rows'));
    }

    public function rejected()
    {
        $types = ['PROVIDER', 'FAMILY'];
        $rows = $this->model->whereIn('type', $types)->where('approved', -1)->latest()->get();
        return view('Dashboard.provider.index', compact('rows'));
    }

    public function binned()
    {
        $rows = Provider::where('approved', 0)->latest()->get();
        return view('Dashboard.provider.binned', compact('rows'));
    }

    public function request_update()
    {
        $rows = Provider::where('request_update', 1)->latest()->get();
        return view('Dashboard.provider.request_update', compact('rows'));
    }

    public function show($id): object
    {
        $user = $this->model->find($id);
        return view('Dashboard.provider.show', compact('user'));
    }

    public function show_request($id): object
    {
        $user = $this->model->find($id);
        return view('Dashboard.provider.show_request', compact('user'));
    }

    public function reject($id, Request $request): object
    {
        $provider = $this->model->find($id);
        $provider->update(
            [
                'approved' => -1,
                'reject_reason' => $request['reject_reason'],
            ]
        );
        $provider->refresh();
        $push = new PushNotification('fcm');
        $message = 'تم رفض انضمامك للسبب التالي :' . $request['reject_reason'];
        $providersTokens = [];
        if ($provider->devices != null) {
            $providersTokens = $provider->devices;
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
            ->setDevicesToken($providersTokens)
            ->send()
            ->getFeedback();
        Notification::create([
            'receiver_id' => $id,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);
        $provider->refresh();
        return redirect()->back()->with('updated');
    }
    public function reject_request($id, Request $request): object
    {
        $provider = $this->model->find($id);
        $provider->update(
            [
                'request_update' => 0,
                'data_for_update' => null,
            ]
        );
        $provider->refresh();
        $push = new PushNotification('fcm');
        $message = 'تم رفض تعديل بياناتك للسبب التالي :' . $request['reject_reason'];
        $providersTokens = [];
        if ($provider->devices != null) {
            $providersTokens = $provider->devices;
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
            ->setDevicesToken($providersTokens)
            ->send()
            ->getFeedback();
        Notification::create([
            'receiver_id' => $id,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);
        $provider->refresh();
        return redirect()->back()->with('updated');
    }

    public function accept($id)
    {
        $provider = $this->model->find($id);
        $provider->update(
            [
                'approved' => 1,
                'approved_at' => Carbon::now()
            ]
        );
        $provider->refresh();
        $push = new PushNotification('fcm');
        $message = 'تم قبول انضمامك :)';
        $providersTokens = [];
        if ($provider->devices != null) {
            $providersTokens = $provider->devices;
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
            ->setDevicesToken($providersTokens)
            ->send()
            ->getFeedback();
        Notification::create([
            'receiver_id' => $id,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);
        $provider->refresh();
        return redirect()->back()->with('updated');
    }
    public function accept_request($id)
    {
        $provider = $this->model->find($id);
        $user = User::find($provider->user_id);
        $this->updateBankData($provider->data_for_update['banks'], $user, $provider->type);

        $provider_data['name']=$provider->data_for_update['data']['name'];
        $provider_data['phone']=$provider->data_for_update['data']['phone'];
        $provider_data['city_id']=$provider->data_for_update['data']['city_id'];
        $provider_data['district_id']=$provider->data_for_update['data']['district_id'];
        $provider_data['last_ip']=$provider->data_for_update['data']['last_ip'];
        $provider_data['has_delivery']=$provider->data_for_update['data']['has_delivery'];
        $provider_data['delivery_price']=$provider->data_for_update['data']['delivery_price'];
        $provider->update($provider_data);

        $provider->refresh();
        $provider->update(
            [
                'request_update' => 0,
                'data_for_update' => null,
            ]
        );
        $push = new PushNotification('fcm');
        $message = 'تم قبول تعديل ملفك الشخصي بنجاح :)';
        $providersTokens = [];
        if ($provider->devices != null) {
            $providersTokens = $provider->devices;
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
            ->setDevicesToken($providersTokens)
            ->send()
            ->getFeedback();
        Notification::create([
            'receiver_id' => $user->id,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);
        $provider->refresh();
        return redirect()->back()->with('updated');
    }

}
