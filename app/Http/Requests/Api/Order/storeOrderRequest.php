<?php

namespace App\Http\Requests\Api\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class storeOrderRequest extends ApiMasterRequest
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
            'deliver_by' => 'required|in:user,provider,delivery',
            'deliver_at' => 'required|date|after:yesterday',
            'promo_code' => 'nullable|max:100',
            'address' => 'nullable|max:200',
            'user_type' => 'nullable',
        ];
    }
}
