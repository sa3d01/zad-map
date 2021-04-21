<?php

namespace App\Http\Requests\Dashboard\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:110',
            'email' => 'email:rfc,dns|max:90|unique:users,email,' . \request()->user()->id,
            'password' => 'nullable|string|min:6|max:15',
            'image' => 'nullable|mimes:png,jpg,jpeg',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'هذا البريد مسجل من قبل',
            'email.email' => 'هذا البريد غير صالح',
            'image' => 'يوجد مشاك بالصورة المرفقة',
            'password' => 'كلمة المرور يجب ألا تقل عن 6 خانات وﻻ تزيد عن 15 خانة',
        ];
    }
}
