<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiMasterRequest;

class UserRegisterationRequest extends ApiMasterRequest
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
            'name' => 'required|string|max:110',
            'email' => 'email:rfc,dns|max:90|unique:users',
            'phone' => 'required|string|max:90|unique:users',
            'password' => 'required|string|min:6|max:15',
            'city_id' => 'required|numeric|exists:drop_downs,id',
            'district' => 'required',
            'has_delivery' => 'nullable',
            'delivery_price' => 'nullable|numeric',
            'device.id' => 'required',
            'device.os' => 'required|in:android,ios',
            'location.lat' => 'nullable',
            'location.lng' => 'nullable',
            'location.address' => 'nullable',
            'marketer_id' => 'nullable',
            'car.note' => 'nullable',
            'car.brand' => 'nullable',
            'car.color' => 'nullable',
            'car.year' => 'nullable',
            'car.identity' => 'nullable',
            'car.end_insurance_date' => 'nullable',
            'car.insurance_image' => 'nullable',
            'car.identity_image' => 'nullable',
            'car.drive_image' => 'nullable',
            'banks' => 'array',
        ];
    }
    public function messages()
    {
        return [
            'phone.unique' => 'هذا الهاتف مسجل من قبل',
        ];
    }
}
