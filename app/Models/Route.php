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
        'credit_company_id',
        'truck_id',
        'distance',
        'fuel_consumption',
        'emissions',
        'cost',
        'co2_delivered',
        'status',
        'is_early_delivery',
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
            'is_early_delivery' => 'boolean',
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

    public function creditCompany(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CreditCompany::class);
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

    /**
     * Check if this is a multi-trip delivery
     */
    public function isMultiTrip(): bool
    {
        return $this->total_trips > 1;
    }

    /**
     * Get trip progress (e.g., "2/3")
     */
    public function getTripProgress(): string
    {
        return "{$this->trip_number}/{$this->total_trips}";
    }

    /**
     * Get estimated arrival time
     */
    public function getEstimatedArrival(): ?\Carbon\Carbon
    {
        if (!$this->scheduled_at || !$this->estimated_duration_minutes) {
            return null;
        }

        return $this->scheduled_at->addMinutes($this->estimated_duration_minutes);
    }

    /**
     * Check if route is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->status === RouteStatus::COMPLETED) {
            return false;
        }

        $estimatedArrival = $this->getEstimatedArrival();

        return $estimatedArrival && now()->isAfter($estimatedArrival);
    }

    /**
     * Get human-readable duration
     */
    public function getFormattedDuration(): string
    {
        if (!$this->estimated_duration_minutes) {
            return 'N/A';
        }

        $hours = floor($this->estimated_duration_minutes / 60);
        $minutes = $this->estimated_duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Complete the route with realistic completion time
     */
    public function complete()
    {
        if (!in_array($this->status, [RouteStatus::PENDING, RouteStatus::IN_PROGRESS])) {
            throw new \Exception('Can only complete pending or in-progress routes');
        }

        // Calculate realistic completion time based on schedule + duration
        $estimatedCompletion = $this->scheduled_at
            ->copy()
            ->addMinutes($this->estimated_duration_minutes);

        $this->update([
            'status' => RouteStatus::COMPLETED,
            'completed_at' => $estimatedCompletion,
        ]);

        // Release truck back to available
        $this->truck->update([
            'available_status' => TruckStatus::AVAILABLE,
            'production_site_id' => null,
            'delivery_company_id' => null,
        ]);

        return $this;
    }

    /**
     * Start this route (mark as in progress)
     */
    public function start(): void
    {
        $this->update([
            'status' => RouteStatus::IN_PROGRESS,
        ]);
    }

    /**
     * Get cost per tonne of CO2
     */
    public function getCostPerTonne(): float
    {
        if ($this->co2_delivered <= 0) {
            return 0;
        }

        return round((float) $this->cost / $this->co2_delivered, 2);
    }

    /**
     * Get emissions per tonne of CO2 delivered
     */
    public function getEmissionsPerTonne(): float
    {
        if ($this->co2_delivered <= 0) {
            return 0;
        }

        return round($this->emissions / $this->co2_delivered, 2);
    }

    /**
     * Get all related trips (for multi-trip deliveries)
     */
    public function getRelatedTrips()
    {
        if (!$this->isMultiTrip()) {
            return collect([$this]);
        }

        return static::where('delivery_company_id', $this->delivery_company_id)
            ->where('production_site_id', $this->production_site_id)
            ->where('truck_id', $this->truck_id)
            ->where('week_number', $this->week_number)
            ->where('year', $this->year)
            ->orderBy('trip_number')
            ->get();
    }

    /**
     * Get total CO2 for all trips in this delivery
     */
    public function getTotalDeliveryCapacity(): float
    {
        return $this->getRelatedTrips()->sum('co2_delivered');
    }
}
