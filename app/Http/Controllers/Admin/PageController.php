<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends MasterController
{
    public function __construct(Page $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function page($type,$for): object
    {
        $page = $this->model->where(['type'=>$type,'for'=>$for])->first();
        return view('Dashboard.page.edit', compact('page'));
    }

    public function update($id, Request $request)
    {
        $page = $this->model->find($id);
        $page->update($request->all());
        return redirect()->back()->with('updated');

    }

}
