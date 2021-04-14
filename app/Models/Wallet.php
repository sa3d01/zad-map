<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profits', 'debtors'];

    public function user(): object
    {
        return $this->belongsTo(User::class);
    }
}
