<?php

namespace App\Http\Controllers\Api\Notification;

use App\Http\Controllers\Api\MasterController;
use App\Http\Resources\NotificationCollection;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use function request;

class NotificationController extends MasterController
{
    protected $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function index():object
    {
        $notifies = new NotificationCollection($this->model->where('receiver_id', request()->user()->id)->latest()->get());
        $unread = $this->model->where('receiver_id', request()->user()->id)->where('read', 'false')->count();
        return $this->sendResponse(['data' => $notifies, 'unread' => $unread]);
    }

    public function show($id):object
    {
        if (!$this->model->find($id))
            return $this->sendError('not found');
        $single = $this->model->find($id);
        $single->update([
            'read' => 'true'
        ]);
        return $this->sendResponse(NotificationResource::make($this->model->find($id)));
    }




}
