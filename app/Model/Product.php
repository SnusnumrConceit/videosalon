<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['name', 'year', 'description', 'cover', 'price', 'activity'];

    public function audios()
    {
//        return $this->belongsToMany('App\Model\Audio', 'audios', 'id', 'audio_id');
    }

    public function genres()
    {
//        return $this->belongsToMany('App\Model\Audio', 'audios', 'id', 'audio_id');
    }

    public function screens()
    {
        return $this->belongsToMany('App\Model\Audio', 'screens', 'product_id', 'id');
    }

    public function videos()
    {
        return $this->belongsToMany('App\Model\Audio', 'videos', 'video_id', 'id');
    }
}
