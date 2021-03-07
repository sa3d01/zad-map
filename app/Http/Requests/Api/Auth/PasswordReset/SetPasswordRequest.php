<?php

namespace App\Http\Requests\Api\Auth\PasswordReset;

use App\Http\Requests\Api\ApiMasterRequest;

class SetPasswordRequest extends ApiMasterRequest
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
            'phone' => 'required|string|max:90|exists:users',
            'code' => 'required|numeric|max:99999',
            'password' => 'required|string|max:10',
        ];
    }
}
