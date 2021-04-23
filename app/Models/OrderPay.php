<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderPay extends Model
{
    use HasFactory;

    private $route='order_pay';
    private $images_link='media/images/transfer/';
    protected $fillable = ['order_id','user_id','provider_id','delivery_id','type','image'];

    public function user():object
    {
        return $this->belongsTo(User::class);
    }
    public function provider():object
    {
        return $this->belongsTo(User::class,'provider_id','id');
    }
    public function delivery():object
    {
        return $this->belongsTo(User::class,'delivery_id','id');
    }
    public function order():object
    {
        return $this->belongsTo(Order::class);
    }

    protected function setImageAttribute($image)
    {
        $filename = $image;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['image'] = $filename;
    }

    protected function getImageAttribute()
    {
        try {
            if ($this->attributes['image']){
                if (filter_var($this->attributes['image'], FILTER_VALIDATE_URL)) {
                    return $this->attributes['image'];
                }else{
                    return asset($this->images_link) . '/' . $this->attributes['image'];
                }
            }
            return asset($this->images_link) . '/default.png';
        } catch (\Exception $e) {
            return asset($this->images_link) . '/default.png';
        }
    }

    private function upload_file($file)
    {
        $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move($this->images_link, $filename);
        return $filename;
    }
}
