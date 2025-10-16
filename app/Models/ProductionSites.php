<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSites extends Model
{
    use HasFactory;
    protected $fillable = ['co2_production_sources', 'location', 'type', 'system_operating_status', 'annual_production', 'weekly_production', 'shutdown_periods', 'buffer_tank_size'];
}
