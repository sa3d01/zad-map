<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Api\MasterController;
use App\Models\Page;

class PageController extends MasterController
{
    protected $model;

    public function __construct(Page $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function getPage($user_type,$type)
    {
        $page=Page::where(['type'=>$type,'for'=>$user_type])->first();
        if (!$page)
            return $this->sendError('توجد مشكلة بالبيانات');
        $result['title']=$page->title;
        $result['note']=$page->note;
        return $this->sendResponse($result);
    }
}
