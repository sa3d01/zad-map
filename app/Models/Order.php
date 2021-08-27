<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Object_;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'provider_id',
        'delivery_id',
        'deliver_by',
        'deliver_at',
        'delivery_approved_expired',
        'promo_code',
        'address',
        'status',
    ];
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
    public function orderItems():object
    {
        return $this->hasMany(OrderItem::class);
    }
    public function price():int
    {
        $subtotal=0;
        foreach (OrderItem::where('order_id',$this->id)->get() as $orderItem){
            $product_id=$orderItem->cartItem->product_id;
            $product=Product::withTrashed()->find($product_id);
            $subtotal+=$product->price*$orderItem->cartItem->count;
        }
        return (double)$subtotal;
    }
    public function cancelReason(){
        $cancel_reason=CancelOrder::where('order_id',$this['id'])->latest()->first();
        if ($cancel_reason){
            $arr['reason']=$cancel_reason->reason;
            $arr['cancelled_by']=$cancel_reason->user_id;
            return $arr;
        }
        return new Object_();
    }
    public function getStatusArabic():string
    {
        if ($this['status']=='new'){
            return "جديد";
        }elseif ($this['status']=='pre_paid'){
            return "بانتظار الدفع";
        }elseif ($this['status']=='completed'){
            return "مكتمل";
        }elseif ($this['status']=='rejected'){
            return "ملغى";
        }else{
            return "جارى";
        }
    }
}
