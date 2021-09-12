<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NotificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data=[];
        foreach ($this as $obj){
            $arr['id']=(int)$obj->id;
            $arr['type']=$obj->type;
            $arr['read']=$obj->read == 'true';
            $arr['title']=$obj->title;
            $arr['note']=$obj->note;
            if($obj->order_id){
                $arr['order_id']=(int)$obj->order_id;
            }
            $arr['published_from']=Carbon::parse($obj->created_at)->diffForHumans();
            $data[]=$arr;
        }
        return $data;
    }
}
