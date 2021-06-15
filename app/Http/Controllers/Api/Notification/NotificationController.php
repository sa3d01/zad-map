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
        $notifies_q=$this->model->where('receiver_id', request()->user()->id);
        $notifies_q=$notifies_q->whereJsonContains('receivers', request()->user()->id);
        $unread = $notifies_q->where('read', 'false')->count();
        $notifies = new NotificationCollection($notifies_q->latest()->get());
        return $this->sendResponse(['data' => $notifies, 'unread' => $unread]);
    }

    public function readAll():object
    {
        $notifies = new NotificationCollection($this->model->where('receiver_id', request()->user()->id)->latest()->get());
        foreach ($notifies as $single){
            $single->update([
                'read' => 'true'
            ]);
        }
        return $this->sendResponse(['data' => $notifies, 'unread' => 0]);
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
