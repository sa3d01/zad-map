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

    function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

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
    public function providers($category_id)
    {
        $category=Category::find($category_id);
        $provider_ids=$category->products->pluck('user_id');
        $providers_q=User::whereIn('id',$provider_ids);
        $providers_q=$providers_q->where(['approved'=>1,'banned'=>0]);
        if (request()->has('name')){
            $providers_q->where('name',request()->input('name'));
        }
        if (request()->has('city_id')){
            $providers_q->where('city_id',request()->input('city_id'));
        }
        $providers=$providers_q->get();
        $providers_arr=[];
        if (request()->has('lat') && request()->has('lng')){
            foreach ($providers as $provider){
                $provider_lat=$provider->location['lat'];
                $provider_lng=$provider->location['lng'];
                $distance=$this->distance(request()->input('lat'), request()->input('lng'), $provider_lat, $provider_lng, "K");
                if ($distance<15)
                {
                    $providers_arr[]=$provider->id;
                }
            }
            $providers=User::whereIn('id',$providers_arr)->get();
        }


        return $this->sendResponse(new ProviderCollection($providers));
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
