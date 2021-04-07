<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Provider\Product\storeProductRequest;
use App\Http\Resources\ProductCollection;
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

    public function uploadImages(Request $request):object
    {
        $validate = Validator::make($request->all(),
            [
                'images' => 'required',
                'images.*' => 'image|mimes:jpeg,jpg,png,jpg,gif,svg'
            ]
        );
        if ($validate->fails()) {
            return $this->sendError('يوجد مشكلة بالصور المرفقة');
        }
        $data = [];
        for ($i = 0; $i < count($request['images']); $i++) {
            $file = $request['images'][$i];
            $destinationPath = 'media/images/product/';
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $data[] = asset($destinationPath) . '/' . $filename;
        }
        return $this->sendResponse($data);
    }

    public function store(storeProductRequest $request):object
    {
        $user=User::find(auth()->id());
        $category=Category::find($request['category_id']);
        if ($user->products->count()+1 > $category->free_products ){
            return $this->sendError("تخطيت الحد المجانى لهذا التصنيف");
        }
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        Product::create($data);
        return $this->sendResponse([]," تمت الإضافة بنجاح ..");
    }

    public function list($provider_id):object
    {
        $provider=User::find($provider_id);
        if (!$provider){
            return $this->sendError('توجد مشكلة بالبيانات');
        }
        return $this->sendResponse(new ProductCollection(Product::where('user_id',$provider_id)->latest()->get()));
    }
}
