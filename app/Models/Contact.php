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
        'user_type',
        'contact_type_id',
        'message',
        'read',
    ];
    public function user():object
    {
        return $this->belongsTo(User::class);
    }
    public function contactType():object
    {
        return $this->belongsTo(ContactType::class);
    }
}
