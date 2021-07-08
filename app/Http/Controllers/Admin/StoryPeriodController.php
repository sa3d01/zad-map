<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Dashboard\SliderStoreRequest;
use App\Models\StoryPeriod;
use Illuminate\Http\Request;

class StoryPeriodController extends MasterController
{
    public function __construct(StoryPeriod $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('Dashboard.story-period.index', compact('rows'));
    }

    public function create()
    {
        return view('Dashboard.story-period.create');
    }

    public function store(SliderStoreRequest $request)
    {
        $data = $request->all();
        $this->model->create($data);
        return redirect()->route('admin.story-period.index')->with('created');
    }

    public function edit($id):object
    {
        $story_period=$this->model->find($id);
        return view('Dashboard.story-period.edit', compact('story_period'));
    }
    public function update($id,Request $request)
    {
        $story_period=$this->model->find($id);
        $story_period->update($request->all());
        return redirect()->back()->with('updated');

    }



}
