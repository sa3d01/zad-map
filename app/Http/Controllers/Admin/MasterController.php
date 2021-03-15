<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class MasterController extends Controller
{

    protected $model;
    protected $route;
    protected $module_name;
    protected $single_module_name;
    protected $index_fields;
    protected $show_fields;
    protected $create_fields;
    protected $update_fields;
    protected $json_fields;

    public function __construct()
    {
        $this->middleware('auth:admin');
        view()->share(array(
            'module_name' => $this->module_name,
            'single_module_name' => $this->single_module_name,
            'route' => $this->route,
            'index_fields' => $this->index_fields,
            'show_fields' => $this->show_fields,
            'create_fields' => $this->create_fields,
            'update_fields' => $this->update_fields,
            'json_fields' => $this->json_fields,
           ));
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('admin.' . $this->route . '.index', compact('rows'));
    }

    public function create()
    {
        return view('admin.' . $this->route . '.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->validation_func(1), $this->validation_msg());
        $this->model->create($request->all());
        return redirect('admin/' . $this->route . '')->with('created', 'تمت الاضافة بنجاح');
    }

    public function edit($id)
    {
        $row = $this->model->find($id);
        return View('admin.' . $this->route . '.edit', compact('row'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, $this->validation_func(2, $id), $this->validation_msg());
        $obj = $this->model->find($id);
        $obj->update($request->all());
        return redirect()->back()->with('updated', 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        $this->model->find($id)->delete();
        return redirect()->back()->with('deleted', 'تم الحذف بنجاح');
    }

    public function show($id)
    {
        $row = $this->model->find($id);
        return View('admin.' . $this->route . '.show', compact('row'));
    }
}

