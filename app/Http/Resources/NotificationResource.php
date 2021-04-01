<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=> (int)$this->id,
            'type'=> $this->type,
            'read'=> ($this->read == 'true') ? true : false,
            'title'=> $this->title,
            'note'=> $this->note,
            'order_id'=>(int) $this->order_id??0,
            'published_from'=> Carbon::parse($this->created_at)->diffForHumans()
        ];
    }
}
