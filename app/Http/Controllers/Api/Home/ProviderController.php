<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\ProviderCollection;
use App\Models\Provider;
use App\Models\Slider;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;

class ProviderController extends MasterController
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function providersMap():object
    {
        $providers=Provider::where(['approved'=>1,'online'=>1,'banned'=>0])->pluck('user_id')->toArray();
        $data=User::whereIn('id',$providers)->get();
        return $this->sendResponse(new ProviderCollection($data));
    }
}
