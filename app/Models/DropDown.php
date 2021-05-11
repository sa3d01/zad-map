<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropDown extends Model
{
    use HasFactory,ModelBaseFunctions;
    private $route='drop_down';
    private $images_link='media/images/drop_down/';
    protected $fillable = [
        'class',
        'status',
        'name',
        'image',
        'parent_id',
    ];
    public function parent(){
        return $this->belongsTo(DropDown::class,'parent_id','id');
    }
}
