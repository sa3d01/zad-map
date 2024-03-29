<?php

namespace App\Http\Requests\Api\Chat;

use App\Http\Requests\Api\ApiMasterRequest;

class ChatRequest extends ApiMasterRequest
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
            'message' => 'required|max:250',
            'receiver_id' => 'required|exists:users,id',
            'user_type' => 'nullable',
            'order_id' => 'nullable|exists:orders,id',
        ];
    }
}
