<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,ModelBaseFunctions,SoftDeletes;

    private $route='product';
    private $images_link='media/images/product/';
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'note',
        'price',
        'images',
        'status',
        'count',
        'deleted_at',
    ];
    protected $dates = [
        'deleted_at'
    ];
    protected $softDelete = true;
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
