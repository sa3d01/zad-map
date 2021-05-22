<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'delivery_id',
        'delivery_price',
        'status'
    ];
    public function delivery():object
    {
        return $this->belongsTo(User::class,'delivery_id','id');
    }
    public function order():object
    {
        return $this->belongsTo(Order::class);
    }
}
