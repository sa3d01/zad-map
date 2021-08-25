<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;

class LoginRequest extends ApiMasterRequest
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
            'type' => 'nullable|string|max:110',
            'phone' => 'required|string|max:90|exists:users',
            'password' => 'required|string|min:6|max:20',
            'device.id' => 'required',
            'device.os' => 'required|in:android,ios',
        ];
    }

    public function messages()
    {
        return [
            'phone.exists' => 'هذا الهاتف غير مسجل من قبل',
        ];
    }
}
