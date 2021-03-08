<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory,ModelBaseFunctions;
    protected $fillable = [
        'type',
        'for',
        'title',
        'note',
    ];
}
