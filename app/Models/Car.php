<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','note','brand','color','year','identity','insurance_image','end_insurance_date','identity_image','drive_image'];
    private $images_link='media/images/car/';


    public function user():object
    {
        return $this->belongsTo(User::class,'user_id');
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

    protected function setInsuranceImageAttribute($insurance_image)
    {
        $image = $insurance_image;
        $filename = $image;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['insurance_image'] = $filename;
    }

    protected function getInsuranceImageAttribute()
    {
        try {
            if ($this->attributes['insurance_image']){
                if (filter_var($this->attributes['insurance_image'], FILTER_VALIDATE_URL)) {
                    return $this->attributes['insurance_image'];
                }else{
                    return asset($this->images_link) . '/' . $this->attributes['insurance_image'];
                }
            }
            return asset($this->images_link) . '/default.png';
        } catch (\Exception $e) {
            return asset($this->images_link) . '/default.png';
        }
    }

    protected function setIdentityImageAttribute($identity_image)
    {
        $image = $identity_image;
        $filename = $image;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['identity_image'] = $filename;
    }

    protected function getIdentityImageAttribute()
    {
        try {
            if ($this->attributes['identity_image']){
                if (filter_var($this->attributes['identity_image'], FILTER_VALIDATE_URL)) {
                    return $this->attributes['identity_image'];
                }else{
                    return asset($this->images_link) . '/' . $this->attributes['identity_image'];
                }
            }
            return asset($this->images_link) . '/default.png';
        } catch (\Exception $e) {
            return asset($this->images_link) . '/default.png';
        }
    }

    protected function setDriveImageAttribute($drive_image)
    {
        $image = $drive_image;
        $filename = $image;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['drive_image'] = $filename;
    }

    protected function getDriveImageAttribute()
    {
        try {
            if ($this->attributes['drive_image']){
                if (filter_var($this->attributes['drive_image'], FILTER_VALIDATE_URL)) {
                    return $this->attributes['drive_image'];
                }else{
                    return asset($this->images_link) . '/' . $this->attributes['drive_image'];
                }
            }
            return asset($this->images_link) . '/default.png';
        } catch (\Exception $e) {
            return asset($this->images_link) . '/default.png';
        }
    }

}
