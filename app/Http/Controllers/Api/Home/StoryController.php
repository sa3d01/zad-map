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
            if (auth('api')->check()){
                if (request()->header('userType')=='USER'){
                    if (auth('api')->user()->normal_user->city_id != $user->normal_user->city_id){
                        continue;
                    }
                }elseif (request()->header('userType')=='DELIVERY'){
                    if (auth('api')->user()->delivery->city_id != $user->delivery->city_id){
                        continue;
                    }
                }else{
                    if (auth('api')->user()->provider->city_id != $user->provider->city_id){
                        continue;
                    }
                }
            }else{
                continue;
            }


            if (request()->header('userType')=='USER'){
                $arr['user']=[
                    'id'=>$user_id,
                    'name'=>$user->normal_user->name,
                    'image'=>$user->normal_user->image,
                ];
            }elseif (request()->header('userType')=='DELIVERY'){
                $arr['user']=[
                    'id'=>$user_id,
                    'name'=>$user->delivery->name,
                    'image'=>$user->delivery->image,
                ];
            }else{
                $arr['user']=[
                    'id'=>$user_id,
                    'name'=>$user->provider->name,
                    'image'=>$user->provider->image,
                ];
            }

            $stories_data=$stories_of_user->filter(function($story) {
                $approved=Carbon::parse($story->approved_at)->format('Y-M-d');
                $endTime=Carbon::parse($story->approved_at)->addDays($story->storyPeriod->story_period)->format('Y-M-d');
                if (Carbon::now()->between($approved, $endTime)) {
                    return $story;
                }
            });
            $stories=[];
            foreach ($stories_data as $datum){
                $result['id']=$datum->id;
                $result['media']=$datum->media;
                $result['media_type']=$datum->media_type;
                $stories[]=$result;
            }
            if (count($stories) < 1){
                continue;
            }
            $arr['stories']=$stories;
            $results[]=$arr;
        }
        return $this->sendResponse($results);
    }
}
