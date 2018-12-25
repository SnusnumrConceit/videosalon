<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AudioProduct extends Model
{
    protected $table = 'audios_products';

    protected $fillable = ['audio_id', 'product_id'];

    public $timestamps = false;

    protected $primaryKey = null;
}
