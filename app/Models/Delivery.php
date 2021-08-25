<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image',
        'city_id',
        'district_id',
        'location',
        'online',
        'devices',
        'last_login_at',
        'last_ip',
        'marketer_id',
        'approved',
        'reject_reason',
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

    public function rates(): object
    {
        return $this->hasMany(Rate::class, 'rated_id', 'id');
    }
    public function banks(): object
    {
        return $this->hasMany(Bank::class);
    }
    public function car(): object
    {
        return $this->hasOne(Car::class);
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
}