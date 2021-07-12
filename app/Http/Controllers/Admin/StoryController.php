<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Models\Story;
use App\Models\Wallet;
use App\Models\WalletPay;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;

class StoryController extends MasterController
{
    public function __construct(Story $model)
    {
        $this->model = $model;
//        $this->middleware('permission:providers');
        parent::__construct();
    }

    public function binned()
    {
        $rows = $this->model->where('status', 'pending')->latest()->get();
        return view('Dashboard.story.binned', compact('rows'));
    }

    public function reject($id, Request $request): object
    {
        $story = $this->model->find($id);
        $story->update(
            [
                'status' => 'rejected',
                'reject_reason' => $request['reject_reason'],
            ]
        );
        $story->refresh();
        return redirect()->back()->with('updated');
    }

    public function accept($id)
    {
        $story = $this->model->find($id);
        $story->update(
            [
                'status' => 'approved',
                'approved_at' => Carbon::now()
            ]
        );
        $data['user_id'] = $story->user_id;
        $data['type'] = 'story';
        $data['amount'] = $story->storyPeriod ? $story->storyPeriod->story_price : 10;
        $data['status'] = 'accepted';
        WalletPay::create($data);
        $wallet = Wallet::where('user_id', $story->user_id)->latest()->first();
        $wallet->update([
            'profits' => $wallet->profits - $story->storyPeriod->story_price,
            'debtors' => $wallet->debtors + $story->storyPeriod->story_price,
        ]);
        $push = new PushNotification('fcm');
        $message = 'تم قبول حالتك';
        $usersTokens = [];
        if ($story->user->device['id'] != 'null') {
            $usersTokens[] = $story->user->device['id'];
        }

        $feed = $push->setMessage([
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
            'receiver_id' => $story->user_id,
            'admin_notify_type' => 'single',
            'title' => $message,
            'note' => $message,
        ]);
        return redirect()->back()->with('updated');
    }

}
