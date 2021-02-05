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
    use HasFactory, SoftDeletes, Notifiable, HasRoles , ModelBaseFunctions;

    private $route='user';
    private $images_link='media/images/user/';
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'device' => 'json',
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $appends = [
        'is_completed_profile',
        'image_url',
    ];

    protected function getIsCompletedProfileAttribute(): bool
    {
        if ($this->attributes['last_login_at']) {
            return true;
        }
        return false;
    }

    public function city(){
        return $this->belongsTo(DropDown::class,'city_id','id');
    }
    public function district(){
        return $this->belongsTo(DropDown::class,'district_id','id');
    }
}
