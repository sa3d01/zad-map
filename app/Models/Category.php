<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,ModelBaseFunctions;
    private $route='category';
    private $images_link='media/images/category/';
    protected $fillable = [
        'name',
        'image',
        'status',
        'free_products',
        'product_price'
    ];
    public function products():object
    {
        return $this->hasMany(Product::class);
    }
}
