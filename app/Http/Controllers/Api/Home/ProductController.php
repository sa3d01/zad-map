<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Provider\Product\storeProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends MasterController
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function show($product_id):object
    {
        $product=Product::find($product_id);
        if (!$product){
            return $this->sendError('توجد مشكلة بالبيانات');
        }
        return $this->sendResponse(new ProductResource($product));
    }
}
