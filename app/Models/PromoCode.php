<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'marketer_id',
        'discount_percent',
        'code',
        'end_date',
        'count_of_uses',
    ];
}
