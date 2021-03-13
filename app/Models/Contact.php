<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory,ModelBaseFunctions;
    protected $fillable = [
        'user_id',
        'contact_type_id',
        'message',
    ];
}
