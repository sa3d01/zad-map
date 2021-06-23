<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Provider\Story\storeStoryRequest;
use App\Models\Story;
use App\Models\StoryPeriod;
use App\Models\Wallet;

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
        $wallet=Wallet::where('user_id',auth('api')->id())->latest()->first();
        if (!$wallet){
            $wallet=Wallet::create([
                'user_id'=>auth('api')->id(),
                'profits'=>0,
                'debtors'=>0
            ]);
        }
        if ($wallet->profits < $story_period->story_price){
            return $this->sendError('يرجي شحن المحفظه بقيمة الاستوري المطلوب اضافتها آولا: '.$story_period->story_price.' ريال ');
        }
        Story::create($data);
        return $this->sendResponse([], " تم الارسال بنجاح .. يرجى انتظار موافقة الإدارة");
    }
}
