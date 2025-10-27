<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    /** @use HasFactory<\Database\Factories\TruckFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'truck_plate',
        'co2_capacity',
        'available_status',
        'truck_type_id',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'co2_capacity' => 'float',
        ];
    }

    /**
     * Get the relationships.
     */
    public function truckType()
    {
        return $this->belongsTo(TruckType::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
