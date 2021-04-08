<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Chat\ChatRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\MessageResource;
use App\Models\Chat;

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

}
