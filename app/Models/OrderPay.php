<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPay extends Model
{
    use HasFactory, ModelBaseFunctions;

    private $route='order_pay';
    private $images_link='media/images/order_pay/';
    protected $fillable = ['order_id','user_id','provider_id','delivery_id','type','image'];

    public function user():object
    {
        return $this->belongsTo(User::class);
    }
    public function provider():object
    {
        return $this->belongsTo(User::class,'provider_id','id');
    }
    public function delivery():object
    {
        return $this->belongsTo(User::class,'delivery_id','id');
    }
    public function order():object
    {
        return $this->belongsTo(Order::class);
    }
}
