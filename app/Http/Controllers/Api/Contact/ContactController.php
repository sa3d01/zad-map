<?php

namespace App\Http\Controllers\Api\Contact;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\ContactRequest;
use App\Models\Contact;
use App\Models\ContactType;

class ContactController extends MasterController
{
    protected $model;

    public function __construct(Contact $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function contactTypes(): object
    {
        $data = ContactType::all();
        $results = [];
        foreach ($data as $datum) {
            $result['id'] = $datum->id;
            $result['name'] = $datum->name;
            $results[] = $result;
        }
        return $this->sendResponse($results);
    }

    public function store(ContactRequest $request): object
    {
        $data = $request->validated();
        $data['user_id'] = auth('api')->id();
        Contact::create($data);
        return $this->sendResponse([], " تم الارسال بنجاح .. يرجى انتظار رد الإدارة");
    }
}
