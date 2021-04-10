<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Resources\Json\ResourceCollection;
use phpDocumentor\Reflection\Types\Object_;

class ChatCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $data = [];
        foreach ($this as $obj) {
            $unread_count=Chat::where(['read'=>false,'room' => $obj->room, 'receiver_id' => auth('api')->id()])->count();
            $arr['unread_count'] = $unread_count;
            $arr['room'] = $obj->room;
            $arr['latest_message'] = new MessageResource($obj);
            $data[] = $arr;
        }
        return $data;
    }
}
