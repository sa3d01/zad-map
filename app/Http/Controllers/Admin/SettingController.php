<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class SettingController extends MasterController
{
    public function __construct(Setting $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function showConfig():object
    {
        $setting=$this->model->first();
        return view('Dashboard.setting.edit', compact('setting'));
    }
    public function updateConfing(Request $request)
    {
        $setting=$this->model->first();
        $setting->update($request->all());
        return redirect()->back()->with('updated');

    }
}
