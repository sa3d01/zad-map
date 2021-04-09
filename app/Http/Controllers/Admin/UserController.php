<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Dashboard\Auth\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends MasterController
{
    public function __construct(User $model)
    {
        $this->model = $model;
//        $this->middleware('permission:view-admins', ['only' => ['index']]);
//        $this->middleware('permission:add-admins', ['only' => ['create']]);
//        $this->middleware('permission:edit-admins', ['only' => ['show','activate']]);
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->where('type','USER')->latest()->get();
        return view('Dashboard.user.index', compact('rows'));
    }

}
