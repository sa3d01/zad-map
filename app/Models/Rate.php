<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rated_id',
        'order_id',
        'rate',
        'feedback',
    ];
    public function order():object
    {
        return $this->belongsTo(Order::class);
    }
    public function user():object
    {
        return $this->belongsTo(User::class);
    }
    public function rated():object
    {
        return $this->belongsTo(User::class,'rated_id','id');
    }
}
