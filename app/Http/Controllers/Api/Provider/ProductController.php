<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Provider\Product\storeProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
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
                'images.*' => 'image'
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
        $category=Category::find($request['category_id']);
        $user_category_products=Product::where(['category_id'=>$request['category_id'],'user_id'=>auth('api')->id()])->count();
        if ($user_category_products+1 > $category->free_products ){
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

    public function delete($id):object
    {
        $product=$this->model->find($id);
        if (!$product || ($product->user_id != auth('api')->id())){
            return $this->sendError('توجد مشكلة بالبيانات');
        }
        $cart_items=CartItem::where('product_id',$id)->get();
        foreach ($cart_items as $cart_item)
        {
            if ($cart_item->cart->ordered==0){
                $cart_item->delete();
            }else{
                $item_orders=OrderItem::where('cart_item_id',$cart_item->id)->get();
                foreach ($item_orders as $item_order){
                    if ($item_order->order->status!='completed' && $item_order->order->status!='rejected'){
                        return $this->sendError('توجد طلبات جارية على هذه السلعة');
                    }
                }
            }
        }
        $product->delete();
        return $this->sendResponse(new ProductCollection(Product::where('user_id',auth('api')->id())->latest()->get()));
    }

    public function update($id,storeProductRequest $request):object
    {
        $product=$this->model->find($id);
        if (!$product || ($product->user_id != auth('api')->id())){
            return $this->sendError('توجد مشكلة بالبيانات');
        }
        $cart_items=CartItem::where('product_id',$id)->get();
        foreach ($cart_items as $cart_item)
        {
            $item_orders=OrderItem::where('cart_item_id',$cart_item->id)->get();
            foreach ($item_orders as $item_order){
                if ($item_order->order->status!='completed' && $item_order->order->status!='rejected'){
                    return $this->sendError('توجد طلبات جارية على هذه السلعة');
                }
            }
        }
        $data = $request->validated();
        $product->update($data);
        return $this->sendResponse(new ProductCollection(Product::where('user_id',auth('api')->id())->latest()->get()));
    }
}
