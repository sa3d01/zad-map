<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Provider\Product\storeProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProviderResourse;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProviderController extends MasterController
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
        parent::__construct();
    }

   public function show($id)
   {
       $provider=User::find($id);
       if (!$provider){
           return $this->sendError('توجد مشكلة بالبيانات');
       }
       return $this->sendResponse(new ProviderResourse($provider));
   }


}
