<?php

namespace App\Http\Requests\Api\Provider\Product;

use App\Http\Requests\Api\ApiMasterRequest;

class storeProductRequest extends ApiMasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id' => 'required|numeric|exists:categories,id',
            'name' => 'required',
            'note' => 'required',
            'price' => 'required|numeric',
            'images' => 'required',
        ];
    }
}
