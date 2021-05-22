<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Dashboard\SliderStoreRequest;
use App\Models\Slider;

class SliderController extends MasterController
{
    public function __construct(Slider $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('Dashboard.slider.index', compact('rows'));
    }

    public function create()
    {
        return view('Dashboard.slider.create');
    }

    public function store(SliderStoreRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $this->model->create($data);
        return redirect()->route('admin.slider.index')->with('created');
    }

    public function ban($id): object
    {
        $slider = $this->model->find($id);
        $slider->update(
            [
                'status'=>0,
            ]
        );
        $slider->refresh();
        $slider->refresh();
        return redirect()->back()->with('updated');
    }
    public function activate($id):object
    {
        $slider=$this->model->find($id);
        $slider->update(
            [
                'status'=>1,
            ]
        );
        $slider->refresh();
        $slider->refresh();
        return redirect()->back()->with('updated');
    }

}
