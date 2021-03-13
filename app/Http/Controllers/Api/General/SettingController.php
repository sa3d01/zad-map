<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\DropDownCollection;
use App\Models\DropDown;
use App\Models\Page;
use App\Models\Setting;

class SettingController extends MasterController
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function getSettings()
    {
        $setting = Setting::first();
        $data = [];
        $data['verify_period'] = (integer)$setting->verify_period;
        $data['app_tax'] = (integer)$setting->app_tax;
        $data['mobile'] = $setting->mobile;
        $data['email'] = $setting->email;
        $data['socials'] = $setting->socials;
        return $this->sendResponse($data);
    }
}
