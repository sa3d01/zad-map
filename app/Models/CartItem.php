<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id','product_id','count'];

    public function cart():object
    {
        return $this->belongsTo(Cart::class);
    }
    public function product():object
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
