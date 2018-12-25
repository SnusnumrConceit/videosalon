<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table = 'audio';

    protected $fillable = ['name'];

    public $timestamps = false;
}
