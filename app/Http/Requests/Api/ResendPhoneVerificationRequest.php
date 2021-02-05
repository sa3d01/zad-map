<?php

namespace App\Http\Requests\Api;

use App\Utils\PreparePhone;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ResendPhoneVerificationRequest extends ApiMasterRequest
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
        ];
    }

}
