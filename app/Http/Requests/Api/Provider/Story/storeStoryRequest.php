<?php

namespace App\Http\Requests\Api\Provider\Story;

use App\Http\Requests\Api\ApiMasterRequest;

class storeStoryRequest extends ApiMasterRequest
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
            'story_period_id' => 'required|numeric|exists:story_periods,id',
            'media_type' => 'required|in:image,video',
            'media' => 'required',
            'user_type' => 'nullable',
        ];
    }
//    public function messages()
//    {
//        return [
//            'media' => 'هذا الهاتف مسجل من قبل',
//        ];
//    }
}
