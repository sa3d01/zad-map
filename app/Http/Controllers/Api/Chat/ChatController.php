<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Chat\ChatRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Notification;
use Carbon\Carbon;
use Edujugon\PushNotification\PushNotification;

class ChatController extends MasterController
{
    protected $model;

    public function __construct(Chat $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    function paginateRes($data)
    {
        $res['current_page']= collect($data)['current_page'];
        $res['first_page_url']= collect($data)['first_page_url'];
        $res['from']= collect($data)['from'];
        $res['next_page_url']= collect($data)['next_page_url'];
        $res['path']= collect($data)['path'];
        $res['per_page']= collect($data)['per_page'];
        $res['prev_page_url']= collect($data)['prev_page_url'];
        $res['to']= collect($data)['to'];
        return $res;
    }
    public function getConversations()
    {
        $chat_ids = Chat::where('sender_id', auth('api')->id())->orWhere('receiver_id', auth('api')->id())->pluck('room')->unique();
        $chats = Chat::whereIn('room', $chat_ids)->latest()->simplepaginate(10);
        $data['chats'] = [];
        foreach ($chats as $chat) {
            $unread_count=Chat::where(['read'=>false,'room' => $chat->room, 'receiver_id' => auth('api')->id()])->count();
            $arr['unread_count'] = $unread_count;
            $arr['room'] = $chat->room;
            $arr['latest_message'] = new MessageResource($chat);
            $data['chats'][]= $arr;
        }
        $data['paginate']=$this->paginateRes($chats);
        return $this->sendResponse($data);
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
        $messages = Chat::where('room', $room_id)->latest()->simplepaginate(10);
        $data['chats'] = [];
        foreach ($messages as $message){
            if ($message->receiver_id==auth('api')->id()){
                $message->update([
                    'read'=>true
                ]);
            }
            $arr['id'] = (int)$message->id;
            $arr['message'] = $message->message;
            $arr['sender'] =[
                'id'=>$message->sender_id,
                'name'=>$message->sender->name,
                'image'=>$message->sender->image,
            ];
            $arr['receiver'] =[
                'id'=>$message->receiver_id,
                'name'=>$message->receiver->name,
                'image'=>$message->receiver->image,
            ];
            $arr['by_me'] = $message->sender_id==auth('api')->id();
            $arr['send_from'] = Carbon::parse($message->created_at)->diffForHumans();
            $data['chats'][] = $arr;
        }
        $data['paginate']=$this->paginateRes($messages);
        return $this->sendResponse($data);
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
                'room' => $message->room,
                'message' => new MessageResource($message),
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($user->device['id'])
            ->send();
//        $notification['title'] = $title;
//        $notification['note'] = $title;
//        $notification['receiver_id'] = $user->id;
//        Notification::create($notification);
    }
}
