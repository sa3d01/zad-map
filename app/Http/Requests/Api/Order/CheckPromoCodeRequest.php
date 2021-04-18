<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class CheckPromoCodeRequest extends ApiMasterRequest
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
            'promo_code' => 'required|max:20',
            'total_price' => 'required|max:20',
        ];
    }
}
