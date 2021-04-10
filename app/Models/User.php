<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use phpDocumentor\Reflection\Types\Object_;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, Notifiable, HasRoles , ModelBaseFunctions;

    private $route='user';
    private $images_link='media/images/user/';
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims():array
    {
        return [];
    }

    protected $fillable = [
        'type',
        'name',
        'image',
        'phone',
        'phone_verified_at',
        'password',
        'city_id',
        'district_id',
        'location',
        'email',
        'email_verified_at',
        'banned',
        'device',
        'last_login_at',
        'last_ip',
        'marketer_id',
        'approved',
        'reject_reason',
        'approved_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'device' => 'json',
        'location' => 'json',
    ];

    protected $dates = [
        'deleted_at'
    ];


    protected function getIsCompletedProfileAttribute(): bool
    {
        if ($this->attributes['last_login_at']) {
            return true;
        }
        return false;
    }

    public function city():object
    {
        return $this->belongsTo(DropDown::class,'city_id','id');
    }
    public function district():object
    {
        return $this->belongsTo(DropDown::class,'district_id','id');
    }
    public function products():object
    {
        return $this->hasMany(Product::class);
    }
    public function banks():object
    {
        return $this->hasMany(Bank::class);
    }
    public function rates():object
    {
        return $this->hasMany(Rate::class,'rated_id','id');
    }
    public function car():object
    {
        return $this->hasOne(Car::class);
    }
    public function cartItemsToOrder():object
    {
        $cart=Cart::where(['user_id'=>$this['id'],'ordered'=>0])->latest()->first();
        if ($cart)
        {
            return $cart->cartItems;
        }
        return new Object_();
    }
    public function feedbacks(){
        $feedbacks=[];
        foreach ($this->rates as $rate){
            $arr['feedback']=$rate->feedback;
            $arr['user']['id']=$rate->user->id;
            $arr['user']['name']=$rate->user->name;
            $arr['user']['image']=$rate->user->image;
            $feedbacks[]=$arr;
        }
        return $feedbacks;
    }
    public function averageRate()
    {
        if ($this->rates()->count('rate') < 1){
            return 0;
        }
        return $this->rates()->sum('rate')/$this->rates()->count('rate');
    }

    public function getTypeString():string
    {
        if ($this['type']=='USER'){
            return 'مستخدم';
        }elseif ($this['type']=='PROVIDER'){
            return 'مقدم خدمة';
        }elseif ($this['type']=='FAMILY'){
            return 'أسرة منتجة';
        }else{
            return 'مندوب';
        }
    }

    protected function getImageAttribute():string
    {
        $dest = $this->images_link;
        try {
            if ($this->attributes['image'])
                return asset($dest) . '/' . $this->attributes['image'];
            return asset('media/images/default.jpeg');
        } catch (\Exception $e) {
            return asset('media/images/default.jpeg');
        }
    }
}
