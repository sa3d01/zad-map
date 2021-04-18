<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Api\MasterController;
use App\Models\Slider;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;

class StoryController extends MasterController
{
    protected $model;

    public function __construct(Story $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function index():object
    {
        $users_stories= Story::where('approved_at','!=',null)->get()->groupBy('user_id');
        $results=[];
        foreach ($users_stories as $user_id=>$stories_of_user){
            $user=User::find($user_id);
            $arr['user']=[
                'id'=>$user_id,
                'name'=>$user->name,
                'image'=>$user->image,
            ];
            $stories_data=$stories_of_user->filter(function($story) {
                $approved=Carbon::parse($story->approved_at)->format('Y-M-d');
                $endTime=Carbon::parse($story->approved_at)->addDays($story->storyPeriod->story_period)->format('Y-M-d');
                if (Carbon::now()->between($approved, $endTime)) {
                    if (request()->user()){
                        if (request()->user()->city_id == $story->user->city_id){
                            return $story;
                        }
                    }
                }
            });
            $stories=[];
            foreach ($stories_data as $datum){
                $result['id']=$datum->id;
                $result['media']=$datum->media;
                $result['media_type']=$datum->media_type;
                $stories[]=$result;
            }
            $arr['stories']=$stories;
            $results[]=$arr;
        }
        return $this->sendResponse($results);
    }
}
