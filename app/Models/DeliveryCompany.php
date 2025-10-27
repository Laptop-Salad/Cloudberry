<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    /** @use HasFactory<\Database\Factories\DeliveryCompanyFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'co2_delivery_obligations',
        'location',
        'type', 'cod',
        'annual_min_obligation',
        'annual_max_obligation',
        'weekly_min',
        'weekly_max',
        'buffer_tank_size',
        'constraints'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'annual_min_obligation' => 'float',
            'annual_max_obligation' => 'float',
            'weekly_min' => 'float',
            'weekly_max' => 'float',
            'buffer_tank_size' => 'float',
            'constraints' => 'array'
        ];
    }

    /**
     * Get the relationships.
     */
    public function creditCompany()
    {
        return $this->belongsTo(CreditCompany::class);
    }
    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }
}
