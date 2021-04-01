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
            $arr['name'] = $obj->name;
            $arr['account_number'] = $obj->account_number;
            $data[] = $arr;
        }
        return $data;
    }
}
