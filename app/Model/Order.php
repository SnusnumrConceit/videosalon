<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Model\Product', 'id', 'product_id');
    }
}
