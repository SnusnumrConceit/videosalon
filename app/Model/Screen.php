<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    protected $table = 'screens';

    protected $fillable = ['url', 'product_id'];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Model\Product', 'id', 'product_id');
    }
}
