<?php

namespace App\Http\Requests\Api\Auth\PasswordReset;

use App\Http\Requests\Api\ApiMasterRequest;

class ResendForgotPasswordRequest extends ApiMasterRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_type' => 'nullable',
            'phone' => 'required|string|max:90|exists:users',
        ];
    }
}
