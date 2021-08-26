<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Object_;

class NormalUser extends Model
{
    use HasFactory;
    private $images_link = 'media/images/user/';

    protected $fillable = [
        'user_id',
        'name',
        'image',
        'city_id',
        'district_id',
        'location',
        'devices',
        'last_login_at',
        'last_ip',
        'request_update',
        'data_for_update',
    ];
    protected $casts = [
        'last_login_at' => 'datetime',
        'devices' => 'array',
        'location' => 'json',
    ];
    public function user(): object
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function city(): object
    {
        return $this->belongsTo(DropDown::class, 'city_id', 'id');
    }

    public function district(): object
    {
        return $this->belongsTo(DropDown::class, 'district_id', 'id');
    }

    public function cartItemsToOrder(): object
    {
        $cart = Cart::where(['user_id' => $this['id'], 'ordered' => 0])->latest()->first();
        if ($cart) {
            return $cart->cartItems;
        }
        return new Object_();
    }


    protected function getImageAttribute(): string
    {
        $dest = $this->images_link;
        try {
            if ($this->attributes['image'])
                return asset($dest) . '/' . $this->attributes['image'];
            return asset('media/images/default.png');
        } catch (\Exception $e) {
            return asset('media/images/default.png');
        }
    }

    private function upload_file($file)
    {
        $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move($this->images_link, $filename);
        return $filename;
    }

    function deleteFileFromServer($filePath)
    {
        if ($filePath != null) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    protected function setImageAttribute()
    {
        $image = request('image');
        $filename = null;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['image'] = $filename;
    }
}
