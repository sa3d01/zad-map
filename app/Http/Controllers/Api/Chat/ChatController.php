<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Chat\ChatRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\MessageResource;
use App\Http\Resources\OrderResourse;
use App\Models\Chat;
use App\Models\Notification;
use Edujugon\PushNotification\PushNotification;

class ChatController extends MasterController
{
    protected $model;

    public function __construct(Chat $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    public function getConversations(): object
    {
        $chat_ids = Chat::where('sender_id', auth('api')->id())->orWhere('receiver_id', auth('api')->id())->pluck('room')->unique();
        $chats = Chat::whereIn('room', $chat_ids)->latest()->get()->unique('room');
        return $this->sendResponse(new ChatCollection($chats));
    }

    public function store(ChatRequest $request): object
    {
        $data = $request->validated();
        $data['sender_id'] = auth('api')->id();
        $pre_msg = Chat::where(['sender_id' => $data['sender_id'], 'receiver_id' => $data['receiver_id']])->orWhere(['sender_id' => $data['receiver_id'], 'receiver_id' => $data['sender_id']])->first();
        if ($pre_msg) {
            $data['room'] = $pre_msg->id;
            $message = Chat::create($data);
        } else {
            $message = Chat::create($data);
            $message->update([
                'room' => $message->id
            ]);
        }
        $this->notify_receiver($message->receiver,'تم إرسال رسالة جديدة من قبل '.auth('api')->user()->name, $message);
        $messages = Chat::where('room', $message->room)->latest()->get();
        return $this->sendResponse(MessageResource::collection($messages));
    }

    public function getMessages($room_id): object
    {
        $messages = Chat::where('room', $room_id)->latest()->get();
        foreach ($messages as $message){
            if ($message->receiver_id==auth('api')->id()){
                $message->update([
                    'read'=>true
                ]);
            }
        }
        return $this->sendResponse(MessageResource::collection($messages));
    }

    public function notify_receiver($user,$title, $message)
    {
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => array('title' => $title, 'sound' => 'default'),
            'data' => [
                'title' => $title,
                'body' => $title,
                'type' => 'chat',
                'message' => new MessageResource($message),
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($user->device['id'])
            ->send();
        $notification['title'] = $title;
        $notification['note'] = $title;
        $notification['receiver_id'] = $user->id;
        Notification::create($notification);
    }
}
