<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;
use App\Utils\PreparePhone;
use Illuminate\Http\Exceptions\HttpResponseException;

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

    protected function prepareForValidation()
    {
        if ($this->has('phone')) {
            $phone = new PreparePhone($this->phone);
            if (!$phone->isValid()) {
                throw new HttpResponseException(response()->json([
                    'status' =>400,
                    'message' => $phone->errorMsg()
                ], 400));
            }
            $this->merge(['phone' => $phone->getNormalized()]);
        }
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
