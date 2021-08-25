<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        if ($this->sender_type=='normal_user'){
            $sender_name=$this->sender->normal_user->name;
            $sender_image=$this->sender->normal_user->image;
        }elseif ($this->sender_type=='delivery'){
            $sender_name=$this->sender->delivery->name;
            $sender_image=$this->sender->delivery->image;
        }else{
            $sender_name=$this->sender->provider->name;
            $sender_image=$this->sender->provider->image;
        }

        if ($this->receiver_type=='normal_user'){
            $receiver_name=$this->receiver->normal_user->name;
            $receiver_image=$this->receiver->normal_user->image;
        }elseif ($this->receiver_type=='delivery'){
            $receiver_name=$this->receiver->delivery->name;
            $receiver_image=$this->receiver->delivery->image;
        }else{
            $receiver_name=$this->receiver->provider->name;
            $receiver_image=$this->receiver->provider->image;
        }
        return [
            'id' => (int)$this->id,
            'message' => $this->message,
            'sender' =>[
                'id'=>$this->sender_id,
                'name'=>$sender_name,
                'image'=>$sender_image,
            ],
            'receiver' =>[
                'id'=>$this->receiver_id,
                'name'=>$receiver_name,
                'image'=>$receiver_image,
            ],
            'by_me' => $this->sender_id==auth('api')->id(),
            'send_from' => Carbon::parse($this->created_at)->format('H:i A'),
        ];
    }
}
