<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    /** @use HasFactory<\Database\Factories\RouteFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'production_site_id',
        'delivery_company_id',
        'truck_id',
        'distance',
        'emissions',
        'co2_delivered',
        'status',
        'scheduled_at',
        'completed_at'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'distance' => 'float',
            'emissions' => 'float',
            'co2_delivered' => 'float',
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the relationships.
     */
    public function productionSite(){
        return $this->belongsTo(ProductionSite::class);
    }

    public function deliveryCompany(){
        return $this->belongsTo(DeliveryCompany::class);
    }

    public function truck(){
        return $this->belongsTo(Truck::class);
    }
}
