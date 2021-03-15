<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Api\MasterController;
use App\Models\Slider;
use App\Models\Story;
use Carbon\Carbon;

class StoryController extends MasterController
{
    protected $model;

    public function __construct(Story $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function index()
    {
        $data=Story::where('approved_at','!=',null)->get()->filter(function($story) {
            $approved=Carbon::parse($story->approved_at)->format('Y-M-d');
            $endTime=Carbon::parse($story->approved_at)->addDays($story->storyPeriod->story_period)->format('Y-M-d');
            if (Carbon::now()->between($approved, $endTime)) {
                return $story;
            }
        });
        $results=[];
        foreach ($data as $datum){
            $result['id']=$datum->id;
            $result['media']=$datum->media;
            $result['media_type']=$datum->media_type;
            $result['user']=[
                'id'=>$datum->user_id,
                'name'=>$datum->user->name,
            ];
            $results[]=$result;
        }
        return $this->sendResponse($results);
    }
}
