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
            $unread_count = Chat::where(['read' => false, 'room' => $chat->room, 'receiver_id' => auth('api')->id()])->count();
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
        $receiver_model = NormalUser::where('user_id', $request['receiver_id'])->first();
        $data['receiver_type'] = 'USER';
        if (request()->header('userType')=='USER') {
            $sender_model = NormalUser::where('user_id', $data['sender_id'])->first();
            if (Delivery::where('user_id', $request['receiver_id'])->first()) {
                $receiver_model = Delivery::where('user_id', $request['receiver_id'])->first();
                $data['receiver_type'] = 'DELIVERY';
            } else {
                $receiver_model = Provider::where('user_id', $request['receiver_id'])->first();
                $data['receiver_type'] = $receiver_model->type;
            }
        } elseif (request()->header('userType')=='DELIVERY') {
            $sender_model = Delivery::where('user_id', $data['sender_id'])->first();
        } else {
            $sender_model = Provider::where('user_id', $data['sender_id'])->first();
        }

        if ($request['order_id']) {
            $order = Order::find($request['order_id']);
            if ($data['sender_type'] == 'PROVIDER' || $data['receiver_type'] == 'PROVIDER') {
                $data['room'] = $request['order_id'] . $order->provider_id;
            } else {
                $data['room'] = $request['order_id'] . $order->delivery_id;
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

            if ($message->sender->normal_user) {
                $arr['sender'] = [
                    'id' => $message->sender_id,
                    'name' => $message->sender->normal_user->name,
                    'image' => $message->sender->normal_user->image,
                ];
            } elseif ($message->sender->delivery) {
                $arr['sender'] = [
                    'id' => $message->sender_id,
                    'name' => $message->sender->delivery->name,
                    'image' => $message->sender->delivery->image,
                ];
            } else {
                $arr['sender'] = [
                    'id' => $message->sender_id,
                    'name' => $message->sender->provider->name,
                    'image' => $message->sender->provider->image,
                ];
            }

            if ($message->receiver->normal_user) {
                $arr['receiver'] = [
                    'id' => $message->receiver_id,
                    'name' => $message->receiver->normal_user->name,
                    'image' => $message->receiver->normal_user->image,
                ];
            } elseif ($message->receiver->delivery) {
                $arr['receiver'] = [
                    'id' => $message->receiver_id,
                    'name' => $message->receiver->delivery->name,
                    'image' => $message->receiver->delivery->image,
                ];
            } else {
                $arr['receiver'] = [
                    'id' => $message->receiver_id,
                    'name' => $message->receiver->provider->name,
                    'image' => $message->receiver->provider->image,
                ];
            }
            $arr['by_me'] = $message->sender_id == auth('api')->id();
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
