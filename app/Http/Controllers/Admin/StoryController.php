<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Dashboard\Auth\ProfileUpdateRequest;
use App\Models\Story;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletPay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends MasterController
{
    public function __construct(Story $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function binned()
    {
        $rows = $this->model->where('status','pending')->latest()->get();
        return view('Dashboard.story.binned', compact('rows'));
    }
    public function reject($id,Request $request):object
    {
        $story=$this->model->find($id);
        $story->update(
            [
                'status'=>'rejected',
                'reject_reason'=>$request['reject_reason'],
            ]
        );
        $story->refresh();
        return redirect()->back()->with('updated');
    }
    public function accept($id)
    {
        $story=$this->model->find($id);
        $story->update(
            [
                'status'=>'approved',
                'approved_at'=>Carbon::now()
            ]
        );
        $data['user_id'] = $story->user_id;
        $data['type'] = 'story';
        $data['amount'] = $story->storyPeriod?$story->storyPeriod->story_price:10;
        $data['status'] = 'accepted';
        WalletPay::create($data);
        $wallet=Wallet::where('user_id',$story->user_id)->latest()->first();
        $wallet->update([
           'profits'=>$wallet->profits- $story->storyPeriod->story_price,
           'debtors'=>$wallet->debtors+ $story->storyPeriod->story_price,
        ]);
        $story->refresh();
        $story->refresh();
        return redirect()->back()->with('updated');
    }

}
