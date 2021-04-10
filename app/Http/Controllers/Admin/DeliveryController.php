<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Dashboard\Auth\ProfileUpdateRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends MasterController
{
    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->where('type','DELIVERY')->where('approved',1)->latest()->get();
        return view('Dashboard.delivery.index', compact('rows'));
    }
    public function binned()
    {
        $rows = $this->model->where('type','DELIVERY')->where('approved',0)->latest()->get();
        return view('Dashboard.delivery.binned', compact('rows'));
    }
    public function show($id):object
    {
        $user=$this->model->find($id);
        return view('Dashboard.delivery.show', compact('user'));
    }
    public function reject($id,Request $request):object
    {
        $user=$this->model->find($id);
        $user->update(
            [
                'approved'=>-1,
                'reject_reason'=>$request['reject_reason'],
            ]
        );
        $user->refresh();
        return redirect()->back()->with('updated');
    }
    public function accept($id)
    {
        $user=$this->model->find($id);
        $user->update(
            [
                'approved'=>1,
                'approved_at'=>Carbon::now()
            ]
        );
        $user->refresh();
        return redirect()->back()->with('updated');
    }

}
