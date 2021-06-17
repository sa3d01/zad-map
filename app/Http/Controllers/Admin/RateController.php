<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Rate;

class RateController extends MasterController
{
    public function __construct(Rate $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('Dashboard.rate.index', compact('rows'));
    }


}
