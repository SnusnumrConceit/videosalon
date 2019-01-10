<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['name', 'year', 'description', 'cover', 'price', 'activity'];

    public function audios()
    {
        return $this->belongsToMany(
            'App\Model\Audio',
            'audios_products',
            'product_id',
            'audio_id');
    }

    public function genres()
    {
        return $this->belongsToMany(
            'App\Model\Genre',
            'genres_products',
            'product_id',
            'genre_id');
    }

    public function screens()
    {
        return $this->hasMany('App\Model\Screen', 'product_id', 'id');
    }

    public function video()
    {
        return $this->hasOne('App\Model\Video', 'product_id', 'id');
    }
}
