<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','note','brand','color','year','identity','insurance_image','end_insurance_date','identity_image','drive_image'];


    public function user():object
    {
        return $this->belongsTo(User::class,'user_id');
    }

    private function upload_file($file)
    {
        $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move('media/images/car/', $filename);
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

    protected function setInsuranceImageAttribute()
    {
        $image = request('image');
        $filename = null;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['insurance_image'] = $filename;
    }

    protected function getInsuranceImageAttribute()
    {
        $dest = 'media/images/car/';
        try {
            if ($this->attributes['insurance_image'])
                return asset($dest) . '/' . $this->attributes['insurance_image'];
            return asset($dest) . '/default.png';
        } catch (\Exception $e) {
            return asset($dest) . '/default.png';
        }
    }

    protected function setIdentityImageAttribute()
    {
        $image = request('image');
        $filename = null;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['identity_image'] = $filename;
    }

    protected function getIdentityImageAttribute()
    {
        $dest = 'media/images/car/';
        try {
            if ($this->attributes['identity_image'])
                return asset($dest) . '/' . $this->attributes['identity_image'];
            return asset($dest) . '/default.png';
        } catch (\Exception $e) {
            return asset($dest) . '/default.png';
        }
    }

    protected function setDriveImageAttribute()
    {
        $image = request('image');
        $filename = null;
        if (is_file($image)) {
            $filename = $this->upload_file($image);
        } elseif (filter_var($image, FILTER_VALIDATE_URL) === True) {
            $filename = $image;
        }
        $this->attributes['drive_image'] = $filename;
    }

    protected function getDriveImageAttribute()
    {
        $dest = 'media/images/car/';
        try {
            if ($this->attributes['drive_image'])
                return asset($dest) . '/' . $this->attributes['drive_image'];
            return asset($dest) . '/default.png';
        } catch (\Exception $e) {
            return asset($dest) . '/default.png';
        }
    }

}
