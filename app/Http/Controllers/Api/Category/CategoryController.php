<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProviderCollection;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

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
    public function providers():object
    {
//        $providers_q=User::where(['approved'=>1,'banned'=>0])->whereHas('products');
        $providers_q=User::whereHas('products');
        if (request()->has('name')){
            $providers_q->where('name',request()->input('name'));
        }
        if (request()->has('city_id')){
            $providers_q->where('city_id',request()->input('city_id'));
        }
        $data=$providers_q->get();
        return $this->sendResponse(new ProviderCollection($data));
    }
    public function products($category_id,$provider_id):object
    {
        $provider=User::find($provider_id);
        $category=User::find($category_id);
        if (!$provider || !$category){
            return $this->sendError('توجد مشكلة بالبيانات');
        }
        return $this->sendResponse(new ProductCollection(Product::where(['user_id'=>$provider_id,'category_id'=>$category_id])->get()));
    }
}
