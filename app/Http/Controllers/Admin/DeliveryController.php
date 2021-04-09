<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Dashboard\Auth\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends MasterController
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
        $rows = $this->model->where('type','DELIVERY')->latest()->get();
        return view('Dashboard.delivery.index', compact('rows'));
    }
    public function show($id):object
    {
        $user=$this->model->find($id);
        return view('Dashboard.delivery.show', compact('user'));
    }
    public function ban($id):object
    {
        $user=$this->model->find($id);
        $user->update(
            [
                'banned'=>1,
            ]
        );
        $user->refresh();
        return redirect()->back()->with('updated');
    }
    public function activate($id):object
    {
        $user=$this->model->find($id);
        $user->update(
            [
                'banned'=>0,
            ]
        );
        $user->refresh();
        return redirect()->back()->with('updated');
    }

}
