<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, Notifiable, HasRoles, ModelBaseFunctions;

    private $route = 'user';
    private $images_link = 'media/images/user/';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
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
        'email',
        'email_verified_at',
        'banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'deleted_at'
    ];
    public function normal_user(): object
    {
        return $this->hasOne(NormalUser::class,'user_id','id');
    }
    public function provider(): object
    {
        return $this->hasOne(Provider::class,'user_id','id');
    }
    public function delivery(): object
    {
        return $this->hasOne(Delivery::class,'user_id','id');
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
