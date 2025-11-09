<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostcodeCache extends Model
{
    protected $fillable = [
        'postcode',
        'latitude',
        'longitude',
    ];
}
