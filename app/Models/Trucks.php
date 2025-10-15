<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trucks extends Model
{
    protected $fillable = ['truck_type', 'co2_capacity', 'number_available'];
}
