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
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'capacity' => 'float',
            'count_available' => 'integer',
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
