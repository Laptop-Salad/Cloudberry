<?php

namespace App\Models;

use App\Enums\TruckStatus;
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
        'production_site_id',
        'delivery_company_id'
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'co2_capacity' => 'float',
            'available_status' => TruckStatus::class,
        ];
    }

    /**
     * Get the relationships.
     */
    public function truckType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TruckType::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function productionSite(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductionSite::class);
    }

    public function deliveryCompany(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DeliveryCompany::class);
    }
}
