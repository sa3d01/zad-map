<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class BankController extends MasterController
{
    public function __construct(Bank $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $users=User::where('type','ADMIN')->pluck('id');
        $rows = $this->model->whereIn('user_id',$users)->latest()->get();
        return view('Dashboard.bank.index', compact('rows'));
    }
    public function create()
    {
        return view('Dashboard.bank.create');
    }
    public function store(Request $request)
    {
        $data=$request->all();
        $data['user_id']=auth()->id();
        $this->model->create($data);
        return redirect()->route('admin.bank.index')->with('created');
    }
    public function ban($id):object
    {
        $bank=$this->model->find($id);
        $bank->update(
            [
                'status'=>0,
            ]
        );
        $bank->refresh();
        return redirect()->back()->with('updated');
    }
    public function activate($id):object
    {
        $bank=$this->model->find($id);
        $bank->update(
            [
                'status'=>1,
            ]
        );
        $bank->refresh();
        return redirect()->back()->with('updated');
    }

}
