<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SliderStoreRequest extends FormRequest
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
            'title' => 'required|string|max:110',
            'note' => 'required|string|max:400',
            'start_date' => 'required|date|after:yesterday',
            'end_date' => 'required||after:today',
            'image' => 'required|mimes:png,jpg,jpeg',
        ];
    }

}
