<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Provider extends Model
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
        'online',
        'devices',
        'marketer_id',
        'approved',
        'reject_reason',
        'has_delivery',
        'delivery_price',
        'request_update',
        'data_for_update',
        'last_login_at',
        'last_ip',
    ];
    protected $casts = [
        'last_login_at' => 'datetime',
        'devices' => 'array',
        'location' => 'json',
        'data_for_update' => 'json',
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

    public function products(): object
    {
        return $this->hasMany(Product::class);
    }

    public function banks(): object
    {
        return $this->hasMany(Bank::class);
    }

    public function rates(): object
    {
        return $this->hasMany(Rate::class, 'rated_id', 'id');
    }

    public function feedbacks()
    {
        $feedbacks = [];
        foreach ($this->rates as $rate) {
            $arr['rate'] = (int)$rate->rate;
            $arr['feedback'] = $rate->feedback;
            $arr['user']['id'] = $rate->user->id;
            $arr['user']['name'] = $rate->user->name;
            $arr['user']['image'] = $rate->user->image;
            $feedbacks[] = $arr;
        }
        return $feedbacks;
    }

    public function averageRate()
    {
        if ($this->rates()->count('rate') < 1) {
            return 0;
        }
        return $this->rates()->sum('rate') / $this->rates()->count('rate');
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

    public function getTypeString():string
    {
        if ($this['type']=='PROVIDER'){
            return 'مقدم خدمة';
        }else{
            return 'أسرة منتجة';
        }
    }
}
