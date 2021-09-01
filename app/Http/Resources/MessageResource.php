<?php

namespace App\Http\Resources;

use App\Models\Delivery;
use App\Models\NormalUser;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        if ($this->sender_type=='USER'){
            $sender_model=NormalUser::where('user_id',$this->sender_id)->first();
        }elseif ($this->sender_type=='DELIVERY'){
            $sender_model=Delivery::where('user_id',$this->sender_id)->first();
        }else{
            $sender_model=Provider::where('user_id',$this->sender_id)->first();
        }
        if ($this->receiver_type=='USER'){
            $receiver_model=NormalUser::where('user_id',$this->receiver_id)->first();
        }elseif ($this->receiver_type=='DELIVERY'){
            $receiver_model=Delivery::where('user_id',$this->receiver_id)->first();
        }else{
            $receiver_model=Provider::where('user_id',$this->receiver_id)->first();
        }
        if ($this->sender_id == auth('api')->id() && $this->sender_type == request()->header('userType')){
            $by_me = true;
        }else{
            $by_me = false;
        }
        return [
            'id' => (int)$this->id,
            'message' => $this->message,
            'order_id' => $this->order_id,
            'room' => $this->room,
            'sender' =>[
                'id'=>$this->sender_id,
                'name'=>$sender_model->name,
                'image'=>$sender_model->image,
                'user_type'=>$this->sender_type,
            ],
            'receiver' =>[
                'id'=>$this->receiver_id,
                'name'=>$receiver_model->name,
                'image'=>$receiver_model->image,
                'user_type'=>$this->receiver_type,
            ],
            'by_me' => $by_me,
            'send_from' => Carbon::parse($this->created_at)->format('H:i A'),
        ];
    }
}
