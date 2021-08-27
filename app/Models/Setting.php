<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'mobile',
        'email',
        'socials',
        'verify_period',
        'notify_paid_period',
        'period_to_delivery_approved',
        'app_tax',
        'km_delivery_price',
        'slider_title',
    ];
    protected $casts = [
        'socials' => 'json',
    ];
}
