<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class RateOrderRequest extends ApiMasterRequest
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
            'provider.rate' => 'required|integer',
            'provider.feedback' => 'nullable|max:150',
            'delivery.rate' => 'nullable|integer',
            'delivery.feedback' => 'nullable|max:150',
            'user_type' => 'nullable',
        ];
    }
}
