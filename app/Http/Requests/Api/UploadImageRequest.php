<?php

namespace App\Http\Requests\Api;

class UploadImageRequest extends ApiMasterRequest
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
            'type' => 'required|in:avatar,insurance_image,identity_image,drive_image,transfer',
            'image' => 'required|image',
        ];
    }
}
