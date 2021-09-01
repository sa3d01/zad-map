<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Api\MasterController;
use App\Http\Requests\Api\Chat\ChatRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Delivery;
use App\Models\NormalUser;
use App\Models\Order;
use App\Models\Provider;
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
        $res['current_page'] = collect($data)['current_page'];
        $res['first_page_url'] = collect($data)['first_page_url'];
        $res['from'] = collect($data)['from'];
        $res['next_page_url'] = collect($data)['next_page_url'];
        $res['path'] = collect($data)['path'];
        $res['per_page'] = collect($data)['per_page'];
        $res['prev_page_url'] = collect($data)['prev_page_url'];
        $res['to'] = collect($data)['to'];
        return $res;
    }

    public function getConversations()
    {
        $chat_ids = Chat::where(['sender_id' => auth('api')->id(), 'sender_type' => request()->header('userType')])->orWhere(['receiver_id' => auth('api')->id(), 'receiver_type' => request()->header('userType')])->pluck('room')->unique();
        $all_chats = Chat::whereIn('room', $chat_ids)->latest()->get();
        $rooms = [];
        $unique_chat_ids = [];
        foreach ($all_chats as $conversation) {
            if (in_array($conversation->room, $rooms)) {
                continue;
            }
            $rooms[] = $conversation->room;
            $unique_chat_ids[] = $conversation->id;
        }
        $chats = Chat::whereIn('id', $unique_chat_ids)->latest()->simplepaginate(10);
        $data['chats'] = [];
        foreach ($chats as $chat) {
            $unread_count = Chat::where(['read' => false, 'room' => $chat->room, 'receiver_id' => auth('api')->id(), 'receiver_type' => request()->header('userType')])->count();
            $arr['unread_count'] = $unread_count;
            $arr['order_id'] = $chat->order_id;
            $arr['room'] = $chat->room;
            $arr['active'] = $chat->order->status != 'completed';
            $arr['latest_message'] = new MessageResource($chat);
            $data['chats'][] = $arr;
        }
        $data['paginate'] = $this->paginateRes($chats);
        return $this->sendResponse($data);
    }

    public function store(ChatRequest $request): object
    {
        $data = $request->validated();
        $data['sender_id'] = auth('api')->id();
        $data['sender_type'] = request()->header('userType');
        if (request()->header('userType')=='USER') {
            $sender_model = NormalUser::where('user_id', $data['sender_id'])->first();
        } elseif (request()->header('userType')=='DELIVERY') {
            $sender_model = Delivery::where('user_id', $data['sender_id'])->first();
        } else {
            $sender_model = Provider::where('user_id', $data['sender_id'])->first();
        }

        if ($request['receiver_type']=='USER'){
            $receiver_model = NormalUser::where('user_id', $request['receiver_id'])->first();
            $data['receiver_type'] = 'USER';
        }elseif ($request['receiver_type']=='DELIVERY'){
            $receiver_model = Delivery::where('user_id', $request['receiver_id'])->first();
            $data['receiver_type'] = 'DELIVERY';
        }else{
            $receiver_model = Provider::where('user_id', $request['receiver_id'])->first();
            $data['receiver_type'] = 'PROVIDER';
        }
        if ($request['order_id']) {
            $order = Order::find($request['order_id']);
            if ($data['sender_type'] == 'PROVIDER' || $data['receiver_type'] == 'PROVIDER') {
                $data['room'] = $request['order_id'] .'7'. $order->provider_id;
            } else {
                $data['room'] = $request['order_id'] .'5'. $order->delivery_id;
            }
        } elseif ($request['room']) {
            $data['room'] = $request['room'];
        } else {
            $pre_msg = Chat::where(['sender_id' => $data['sender_id'], 'receiver_id' => $data['receiver_id']])->orWhere(['sender_id' => $data['receiver_id'], 'receiver_id' => $data['sender_id']])->first();
            if (!$pre_msg) {
                return $this->sendError('حدثت مشكلة');
            }
            $data['room'] = $pre_msg->room;
        }
        $message = Chat::create($data);
        $this->notify_receiver($receiver_model, 'تم إرسال رسالة جديدة من قبل ' . $sender_model->name, $message);
        $messages = Chat::where('room', $message->room)->latest()->get();
        return $this->sendResponse(MessageResource::collection($messages));
    }

    public function getMessages($room_id): object
    {
        $messages = Chat::where('room', $room_id)->latest()->simplepaginate(10);
        $data['chats'] = [];
        foreach ($messages as $message) {
            if ($message->receiver_id == auth('api')->id()) {
                $message->update([
                    'read' => true
                ]);
            }
            $arr['id'] = (int)$message->id;
            $arr['message'] = $message->message;

            if ($message->sender_type=='USER') {
                $sender_model=NormalUser::where('user_id',$message->sender_id)->first();
            } elseif ($message->sender_type=='DELIVERY') {
                $sender_model=Delivery::where('user_id',$message->sender_id)->first();
            } else {
                $sender_model=Provider::where('user_id',$message->sender_id)->first();
            }
            $arr['sender'] = [
                'id' => $message->sender_id,
                'name' => $sender_model->name,
                'image' => $sender_model->image,
            ];

            if ($message->receiver_type=='USER') {
                $receiver_model=NormalUser::where('user_id',$message->receiver_id)->first();
            } elseif ($message->receiver_type=='DELIVERY') {
                $receiver_model=Delivery::where('user_id',$message->receiver_id)->first();
            } else {
                $receiver_model=Provider::where('user_id',$message->receiver_id)->first();
            }
            $arr['receiver'] = [
                'id' => $message->receiver_id,
                'name' => $receiver_model->name,
                'image' => $receiver_model->image,
            ];
            if ($message->sender_id == auth('api')->id() && $message->sender_type == request()->header('userType')){
                $arr['by_me'] = true;
            }else{
                $arr['by_me'] = false;
            }
            $arr['send_from'] = Carbon::parse($message->created_at)->diffForHumans();
            $data['chats'][] = $arr;
        }
        $data['paginate'] = $this->paginateRes($messages);
        return $this->sendResponse($data);
    }

    public function notify_receiver($user, $title, $message)
    {
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => array('title' => $title, 'sound' => 'default'),
            'data' => [
                'title' => $title,
                'body' => $title,
                'type' => 'chat',
                'room' => $message->room,
                'order_id' => $message->order_id,
                'message' => new MessageResource($message),
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($user->devices)
            ->send();
    }
}
