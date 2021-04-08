<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'room',
        'sender_id',
        'receiver_id',
        'message',
        'read',
    ];
    public function sender():object
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }
    public function receiver():object
    {
        return $this->belongsTo(User::class,'receiver_id','id');
    }
}
