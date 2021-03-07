<?php

namespace App\Http\Requests\Api;

use App\Utils\PreparePhone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiMasterRequest extends FormRequest
{
    public function expectsJson()
    {
        return true;
    }

    public function prepareForValidation()
    {
        if ($this->has('phone')) {
            $phone = new PreparePhone($this->phone);
            if (!$phone->isValid()) {
                throw new HttpResponseException(response()->json([
                    'status' => 400,
                    'message' => $phone->errorMsg()
                ], 400));
            }
            $this->merge(['phone' => $phone->getNormalized()]);
        }
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 400,
            'message' => $validator->errors()->first()
        ], 400));
    }
}
