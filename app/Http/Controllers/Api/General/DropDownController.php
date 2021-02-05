<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\DropDownCollection;
use App\Models\DropDown;

class DropDownController extends MasterController
{
    protected $model;

    public function __construct(DropDown $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function cities()
    {
        return $this->sendResponse(new DropDownCollection($this->model->whereClass('City')->get()));
    }

    public function districts($cityId)
    {
        return $this->sendResponse(new DropDownCollection($this->model->where('parent_id', $cityId)->get()));
    }
}
