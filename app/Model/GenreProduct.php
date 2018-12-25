<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GenreProduct extends Model
{
    protected $table = 'genres_products';

    protected $fillable = ['genre_id', 'product_id'];

    public $timestamps = false;

    protected $primaryKey = null;
}
