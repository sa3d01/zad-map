<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bank extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','logo','name','account_number','status'];

    private $route='bank';
    private $images_link='media/images/bank/';

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

    protected function setLogoAttribute()
    {
        $logo = request('logo');
        $filename = null;
        if (is_file($logo)) {
            $filename = $this->upload_file($logo);
        } elseif (filter_var($logo, FILTER_VALIDATE_URL) === True) {
            $filename = $logo;
        }
        $this->attributes['logo'] = $filename;
    }

    protected function getLogoAttribute()
    {
        $dest = $this->images_link;
        try {
            if ($this->attributes['logo'])
                return asset($dest) . '/' . $this->attributes['logo'];
            return asset('media/images/logo.jpeg');
        } catch (\Exception $e) {
            return asset('media/images/logo.jpeg');
        }
    }



}
