<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory,ModelBaseFunctions;
    private $route='product';
    private $images_link='media/images/product/';
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'note',
        'price',
        'has_delivery',
        'delivery_price',
        'images',
    ];
    protected $casts = [
        'images' => 'array',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
}
