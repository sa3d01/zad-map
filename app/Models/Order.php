<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'provider_id',
        'delivered_at',
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
}
