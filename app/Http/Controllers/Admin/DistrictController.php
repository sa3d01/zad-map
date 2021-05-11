<?php

namespace App\Http\Controllers\Admin;

use App\Models\DropDown;
use Illuminate\Http\Request;

class DistrictController extends MasterController
{
    public function __construct(DropDown $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->where('class', 'District')->latest()->get();
        return view('Dashboard.district.index', compact('rows'));
    }

    public function create()
    {
        return view('Dashboard.district.create');
    }

    public function edit($id):object
    {
        $district=$this->model->find($id);
        return view('Dashboard.district.edit', compact('district'));
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $data['class'] = 'District';
        $this->model->create($data);
        return redirect()->route('admin.district.index')->with('created');
    }

    public function update($id,Request $request)
    {
        $district=$this->model->find($id);
        $data = $request->all();
        $data['class'] = 'District';
        $district->update($data);
        return redirect()->route('admin.district.index')->with('created');
    }

    public function ban($id): object
    {
        $bank = $this->model->find($id);
        $bank->update(
            [
                'status' => 0,
            ]
        );
        $bank->refresh();
        return redirect()->back()->with('updated');
    }

    public function activate($id): object
    {
        $bank = $this->model->find($id);
        $bank->update(
            [
                'status' => 1,
            ]
        );
        $bank->refresh();
        return redirect()->back()->with('updated');
    }

}