<?php

namespace App\Models;

use App\Enums\RouteStatus;
use App\Enums\TruckStatus;
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
        'fuel_consumption',
        'emissions',
        'cost',
        'co2_delivered',
        'status',
        'scheduled_at',
        'completed_at',
        'week_number',
        'year',
        'trip_number',
        'total_trips',
        'estimated_duration_minutes',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'distance' => 'float',
            'fuel_consumption' => 'float',
            'emissions' => 'float',
            'cost' => 'decimal:2',
            'co2_delivered' => 'float',
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
            'status' => RouteStatus::class,
            'week_number' => 'integer',
            'year' => 'integer',
            'trip_number' => 'integer',
            'total_trips' => 'integer',
            'estimated_duration_minutes' => 'integer',
        ];
    }

    /**
     * Get the relationships.
     */
    public function productionSite(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductionSite::class);
    }

    public function deliveryCompany(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeliveryCompany::class);
    }

    public function truck(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Truck::class);
    }

    /**
     * Scope: Only routes for current week
     */
    public function scopeCurrentWeek($query)
    {
        return $query->where('week_number', now()->weekOfYear)
            ->where('year', now()->year);
    }

    /**
     * Scope: Only pending routes
     */
    public function scopePending($query)
    {
        return $query->where('status', RouteStatus::PENDING);
    }

    /**
     * Scope: Only in progress routes
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', RouteStatus::IN_PROGRESS);
    }

    /**
     * Scope: Only completed routes
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', RouteStatus::COMPLETED);
    }
}
