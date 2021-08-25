<?php

namespace App\Http\Requests\Api\Provider;

use App\Http\Requests\Api\ApiMasterRequest;

class WalletPayRequest extends ApiMasterRequest
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
            'bank_id' => 'required|numeric|exists:banks,id',
            'amount' => 'required|numeric',
            'image' => 'required|image',
            'user_type' => 'nullable',
        ];
    }
}
