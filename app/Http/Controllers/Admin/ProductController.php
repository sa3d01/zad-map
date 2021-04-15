<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends MasterController
{
    public function __construct(Product $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('Dashboard.product.index', compact('rows'));
    }

    public function show($id):object
    {
        $product=$this->model->withTrashed()->find($id);
        return view('Dashboard.product.show', compact('product'));
    }
    public function ban($id):object
    {
        $product=$this->model->find($id);
        $product->update(
            [
                'status'=>0,
            ]
        );
        $product->refresh();
        return redirect()->back()->with('updated');
    }
    public function activate($id):object
    {
        $product=$this->model->find($id);
        $product->update(
            [
                'status'=>1,
            ]
        );
        $product->refresh();
        return redirect()->back()->with('updated');
    }

}
