<?php

namespace App\Models;

use App\Traits\ModelBaseFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Story extends Model
{
    use HasFactory,ModelBaseFunctions;
    protected $fillable = [
        'user_id',
        'story_period_id',
        'media',
        'media_type',
        'status',
        'approved_at',
    ];

    private function upload_file($file)
    {
        $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
        if (false !== mb_strpos($file->getMimeType(), "image")) {
            $file->move('media/images/story/', $filename);
        } else {
            $file->move('media/videos/story/', $filename);
        }
        return $filename;
    }

    protected function setMediaAttribute()
    {
        $media = request('media');
        $filename = null;
        if (is_file($media)) {
            $filename = $this->upload_file($media);
        } elseif (filter_var($media, FILTER_VALIDATE_URL) === True) {
            $filename = $media;
        }
        $this->attributes['media'] = $filename;
    }
    protected function getMediaAttribute()
    {
        if ($this->attributes['media_type']=='video'){
            $dest='media/videos/story/';
        }else{
            $dest='media/images/story/';
        }
        return asset($dest) . '/' . $this->attributes['media'];
    }
}
