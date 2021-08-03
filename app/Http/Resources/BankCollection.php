<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BankCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $data = [];
        foreach ($this as $obj) {
            $arr['id'] = (int)$obj->id;
            $arr['logo'] = $obj->logo;
            $arr['name'] = $obj->name;
            $arr['account_number'] = $obj->account_number;
            $arr['account_name'] = $obj->account_name;
            $data[] = $arr;
        }
        return $data;
    }
}
