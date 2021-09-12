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
        $arr['id']=(int)$this->id;
        $arr['type']=$this->type;
        $arr['read']=$this->read == 'true';
        $arr['title']=$this->title;
        $arr['note']=$this->note;
        if($this->order_id){
            $arr['order_id']=(int)$this->order_id;
        }
        $arr['published_from']=Carbon::parse($this->created_at)->diffForHumans();
        return $arr;
    }
}
