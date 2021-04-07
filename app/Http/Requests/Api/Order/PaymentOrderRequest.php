<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class PaymentOrderRequest extends ApiMasterRequest
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
            'provider.type' => 'required|in:transfer,cache',
            'provider.image' => 'nullable',
            'delivery.type' => 'nullable|in:transfer,cache',
            'delivery.image' => 'nullable',
        ];
    }
}
