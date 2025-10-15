<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckListings extends Model
{
    protected $fillable = ['truck_type', 'available_status', 'holding'];
}
