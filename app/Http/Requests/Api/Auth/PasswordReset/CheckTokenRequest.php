<?php

namespace App\Http\Requests\Api\Auth\PasswordReset;

use App\Http\Requests\Api\ApiMasterRequest;

class CheckTokenRequest extends ApiMasterRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => 'required|string|max:90|exists:users',
            'code' => 'required|numeric|max:9999',
        ];
    }
}
