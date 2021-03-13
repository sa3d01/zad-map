<?php

namespace App\Http\Requests\Api;

class ContactRequest extends ApiMasterRequest
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
            'contact_type_id' => 'required|numeric|exists:contact_types,id',
            'message'=>'required'
        ];
    }
}
