<?php

namespace App\Http\Controllers\Admin;

use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends MasterController
{
    public function __construct(PromoCode $model)
    {
        $this->model = $model;
//        $this->middleware('permission:rows');
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('Dashboard.promo_code.index', compact('rows'));
    }

    public function create()
    {
        return view('Dashboard.promo_code.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->model->create($data);
        return redirect()->route('admin.promo_code.index')->with('created');
    }

    public function edit($id): object
    {
        $promo_code = $this->model->find($id);
        return view('Dashboard.promo_code.edit', compact('promo_code'));
    }

    public function update($id, Request $request)
    {
        $row = $this->model->find($id);
        $row->update($request->all());
        return redirect()->back()->with('updated');
    }
}
