<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckListings extends Model
{
    use HasFactory;
    protected $fillable = ['truck_type', 'available_status', 'holding'];

    public function trucks()
        {
        return $this->belongsTo(Trucks::class);
        }
}
