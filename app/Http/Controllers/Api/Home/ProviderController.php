<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\ProviderCollection;
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
        $data=User::where(['approved'=>1,'online'=>1,'banned'=>0])->get()->filter(function($provider) {
            if ($provider->type=='PROVIDER' || $provider->type=='FAMILY'){
                return $provider;
            }
        });
        return $this->sendResponse(new ProviderCollection($data));
    }
}
