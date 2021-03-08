<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory,ModelBaseFunctions;
    private $route='slider';
    private $images_link='media/images/slider/';
    protected $fillable = [
        'type',
        'status',
        'start_date',
        'end_date',
        'title',
        'note',
        'image',
        'link',
    ];
}
