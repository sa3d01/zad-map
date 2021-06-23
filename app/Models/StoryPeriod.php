<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryPeriod extends Model
{
    use HasFactory;
    protected $fillable = [
        'story_period',
        'story_price',
    ];
}
