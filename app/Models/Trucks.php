<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trucks extends Model
{
    use HasFactory;
    protected $fillable = ['truck_type', 'co2_capacity', 'number_available'];

    public function truck()
        {
        return $this->hasMany(TruckListings::class);
        }
}
