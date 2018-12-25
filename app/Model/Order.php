<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function product()
    {
        return $this->hasOne('App\Model\Product', 'id', 'product_id');
    }
}
