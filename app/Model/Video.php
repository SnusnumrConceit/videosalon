<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = ['name', 'url', 'product_id'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Model\Product', 'id', 'product_id');
    }
}
