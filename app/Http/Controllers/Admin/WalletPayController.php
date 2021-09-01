<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Models\Notification;
use App\Models\Provider;
use App\Models\Wallet;
use App\Models\WalletPay;
use Edujugon\PushNotification\PushNotification;

class WalletPayController extends MasterController
{
    public function __construct(WalletPay $model)
    {
        $this->model = $model;
//        $this->middleware('permission:wallet_pays');
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->where('type', 'transfer')->latest()->get();
        return view('Dashboard.wallet-pay.index', compact('rows'));
    }

    public function reject($id): object
    {
        $pay = $this->model->find($id);
        $pay->update(
            [
                'status' => 'rejected',
            ]
        );

        if ($pay->user_type=='DELIVERY'){
            $receiver_model=Delivery::where('user_id',$pay->user_id)->first();
            $receiver_type='DELIVERY';
        }else{
            $receiver_model=Provider::where('user_id',$pay->user_id)->first();
            $receiver_type='PROVIDER';
        }
        $push = new PushNotification('fcm');
        $message = 'تم رفض حوالتك البنكية';
        $usersTokens = [];
        if ($receiver_model->devices != null) {
            $usersTokens = $receiver_model->devices;
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
            'receiver_id' => $receiver_model->user_id,
            'receiver_type' =>$receiver_type,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);

        $pay->refresh();
        return redirect()->back()->with('updated');
    }

    public function accept($id)
    {
        $wallet_pay = $this->model->find($id);
        $wallet_pay->update(
            [
                'status' => 'accepted',
            ]
        );
        if ($wallet_pay->user_type=='DELIVERY'){
            $receiver_model=Delivery::where('user_id',$wallet_pay->user_id)->first();
            $receiver_type='DELIVERY';
        }else{
            $receiver_model=Provider::where('user_id',$wallet_pay->user_id)->first();
            $receiver_type='PROVIDER';
        }
        $push = new PushNotification('fcm');
        $message = 'تم قبول حوالتك البنكية';
        $usersTokens = [];
        if ($receiver_model->devices != null) {
            $usersTokens = $receiver_model->devices;
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
            'receiver_id' => $receiver_model->user_id,
            'receiver_type' =>$receiver_type,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);

        $wallet = Wallet::where(['user_id'=> $wallet_pay->user_id,'user_type'=>$wallet_pay->user_type])->latest()->first();
        $new_profits=$wallet->profits + $wallet_pay->amount;
        $new_debtors=0;
        if ($wallet->debtors > $new_profits){
            $new_debtors=$wallet->debtors - $new_profits;
            $new_profits=0;
        }elseif ($wallet->debtors != 0){
            $new_profits=$new_profits - $wallet->debtors;
            $new_debtors=0;
        }
        $wallet->update([
            'profits' => $new_profits,
            'debtors' => $new_debtors,
        ]);

        $wallet_pay->refresh();
        return redirect()->back()->with('updated');
    }

}
