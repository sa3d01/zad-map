<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Provider\Story\storeStoryRequest;
use App\Models\Story;
use App\Models\StoryPeriod;
use App\Models\Wallet;
use App\Models\WalletPay;
use App\Models\Notification;
use Illuminate\Support\Str;

class StoryController extends MasterController
{
    protected $model;

    public function __construct(StoryPeriod $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function storyPeriods(): object
    {
        $data = $this->model->all();
        $results = [];
        foreach ($data as $datum) {
            $result['id'] = $datum->id;
            $result['story_period'] = $datum->story_period;
            $result['story_price'] = $datum->story_price;
            $results[] = $result;
        }
        return $this->sendResponse($results);
    }

    public function store(storeStoryRequest $request): object
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $story_period=StoryPeriod::find($request['story_period_id']);
        $wallet=Wallet::where(['user_id'=>auth('api')->id(),'user_type'=>request()->header('userType')])->latest()->first();
        if (!$wallet){
            $wallet=Wallet::create([
                'user_id'=>auth('api')->id(),
                'user_type'=>request()->header('userType'),
                'profits'=>0,
                'debtors'=>0
            ]);
        }
        if ($wallet->profits < $story_period->story_price){
            $wallet_pay=WalletPay::where([
                'user_id'=>auth('api')->id(),
                'user_type'=>'PROVIDER',
                'status'=>'pending',
                'type'=>'transfer'
            ])->latest()->first();
            if ($wallet_pay){
                return $this->sendError('يرجي انتظار موافقة الإدارة علي حوالتك السابقة. ');
            }else{
                return $this->sendError('يرجي شحن المحفظه بقيمة الاستوري المطلوب اضافتها آولا: '.$story_period->story_price.' ريال ');
            }
        }else{
            $story=Story::create($data);
            $debtors=$story->storyPeriod ? $story->storyPeriod->story_price : 10;
            $this->editWallet($wallet,$debtors);
        }

        Notification::create([
            'receiver_id'=>1,
            'receiver_type' => 'ADMIN',
            'type'=>'admin',
            'title'=>'طلب اضافة استوري',
            'note'=>'طلب اضافة استوري',
            'more_details'=>[
                'type'=>'story',
                'story_id'=>$story->id
            ]
        ]);

        return $this->sendResponse([], " تم الارسال بنجاح .. يرجى انتظار موافقة الإدارة");
    }
    function editWallet($wallet,$debtors)
    {
        $wallet->update([
            'profits'=>$wallet->profits-$debtors,
        ]);
        $WalletPay['user_id'] = auth('api')->id();
        $WalletPay['user_type'] = 'PROVIDER';
        $WalletPay['type'] = 'story';
        $WalletPay['amount'] = $debtors;
        $WalletPay['status'] = 'accepted';
        WalletPay::create($WalletPay);
    }
}
