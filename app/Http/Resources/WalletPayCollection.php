<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use phpDocumentor\Reflection\Types\Object_;

class WalletPayCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        $data = [];
        foreach ($this as $obj) {
            $arr['status'] = $obj->status;
            $arr['amount'] = $obj->amount;
            $arr['type'] = $obj->type;
            $arr['date'] = Carbon::parse($obj->created_at)->format('Y-m-d');
            $data[] = $arr;
        }
        return $data;
    }
}
