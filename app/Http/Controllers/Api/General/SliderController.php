<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\DropDownCollection;
use App\Models\DropDown;
use App\Models\Page;
use App\Models\Slider;
use Carbon\Carbon;

class SliderController extends MasterController
{
    protected $model;

    public function __construct(Slider $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function index(){
        $data=Slider::all()->filter(function($slider) {
            $start_date=Carbon::createFromTimestamp($slider->start_date);
            $end_date=Carbon::createFromTimestamp($slider->end_date);
            if (Carbon::now()->between($start_date, $end_date)) {
                return $slider;
            }
        });
        $results=[];
        foreach ($data as $datum){
            $result['id']=$datum->id;
            $result['title']=$datum->title;
            $result['note']=$datum->note;
            $result['image']=$datum->image;
            $results[]=$result;
        }
        return $this->sendResponse($results);
    }
}
