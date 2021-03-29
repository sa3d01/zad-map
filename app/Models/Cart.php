<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','ordered'];

    public function cartItems():object
    {
        return $this->hasMany(CartItem::class, "cart_id");
    }
    public function user():object
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
