<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSite extends Model
{
    /** @use HasFactory<\Database\Factories\ProductionSiteFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'co2_production_sources',
        'location', 'type',
        'system_operating_status',
        'annual_production',
        'weekly_production',
        'shutdown_periods',
        'buffer_tank_size'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'annual_production' => 'float',
            'weekly_production' => 'float',
            'buffer_tank_size' => 'float',
        ];
    }
}
