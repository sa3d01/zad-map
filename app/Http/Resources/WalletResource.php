<?php

namespace App\Http\Resources;

use App\Models\WalletPay;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int)$this->id,
            'profits' => (double)$this->profits,
            'debtors' => (double)$this->debtors,
            'history'=>new WalletPayCollection(WalletPay::where('user_id',$this->user_id)->latest()->get())
        ];
    }
}
