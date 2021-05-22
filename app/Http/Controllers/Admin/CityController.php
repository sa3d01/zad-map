<?php

namespace App\Http\Controllers\Admin;

use App\Models\DropDown;
use Illuminate\Http\Request;

class CityController extends MasterController
{
    public function __construct(DropDown $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->where('class', 'City')->latest()->get();
        return view('Dashboard.city.index', compact('rows'));
    }

    public function create()
    {
        return view('Dashboard.city.create');
    }

    public function edit($id):object
    {
        $city=$this->model->find($id);
        return view('Dashboard.city.edit', compact('city'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['class'] = 'City';
        $this->model->create($data);
        return redirect()->route('admin.city.index')->with('created');
    }

    public function update($id,Request $request)
    {
        $city=$this->model->find($id);
        $data = $request->all();
        $data['class'] = 'City';
        $city->update($data);
        return redirect()->route('admin.city.index')->with('created');
    }

    public function ban($id): object
    {
        $city = $this->model->find($id);
        $city->update(
            [
                'status' => 0,
            ]
        );
        $city->refresh();
        return redirect()->back()->with('updated');
    }

    public function activate($id): object
    {
        $city = $this->model->find($id);
        $city->update(
            [
                'status' => 1,
            ]
        );
        $city->refresh();
        return redirect()->back()->with('updated');
    }

}
