<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletPay extends Model
{
    use HasFactory, ModelBaseFunctions;

    private $route='wallet_pay';
    private $images_link='media/images/transfer/';
    protected $fillable = ['user_id','amount','type','bank_id','image','status'];

    public function user():object
    {
        return $this->belongsTo(User::class);
    }
    public function bank():object
    {
        return $this->belongsTo(Bank::class);
    }
}
