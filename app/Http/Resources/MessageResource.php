<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int)$this->id,
            'message' => $this->message,
            'sender' =>[
                'id'=>$this->sender_id,
                'name'=>$this->sender->name,
                'image'=>$this->sender->image,
            ],
            'receiver' =>[
                'id'=>$this->receiver_id,
                'name'=>$this->receiver->name,
                'image'=>$this->receiver->image,
            ],
            'send_from' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}
