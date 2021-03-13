<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Api\MasterController;
use App\Models\Category;

class CategoryController extends MasterController
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function index():object
    {
        $results=[];
        foreach (Category::where('status',1)->get() as $datum){
            $result['id']=$datum->id;
            $result['name']=$datum->name;
            $result['image']=$datum->image;
            $result['free_products']=$datum->free_products;
            $results[]=$result;
        }
        return $this->sendResponse($results);
    }
}
