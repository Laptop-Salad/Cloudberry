<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckType extends Model
{
    /** @use HasFactory<\Database\Factories\TruckTypeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'capacity',
        'count_available',
        'fuel_consumption_per_km',
        'emission_factor',
        'fuel_cost_per_km',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'capacity' => 'float',
            'count_available' => 'integer',
            'fuel_consumption_per_km' => 'float',
            'emission_factor' => 'float',
            'fuel_cost_per_km' => 'decimal:4',
        ];
    }

    /**
     * Get the relationships.
     */
    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }
}
