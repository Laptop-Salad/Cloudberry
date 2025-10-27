<?php

namespace App\Models;

use App\Enums\ConstraintType;
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
        'buffer_tank_size',
        'constraints',
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
            'constraints'=>'array',
        ];
    }

    /**
     * Get the relationships.
     */
    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function trucks()
    {
        return $this->hasMany(Truck::class);
    }

    /**
     * Get constraints with readable keys.
     */
    public function getReadableConstraints(): array
    {
        $readable = [];
        foreach ($this->constraints ?? [] as $key => $value) {
            $type = ConstraintType::tryFrom((int) $key);
            $readable[$type?->name ?? "Unknown"] = $value;
        }
        return $readable;
    }

    /**
     * Set constraint.
     */
    public function setConstraint(ConstraintType $type, $value): void
    {
        $data = $this->constraints ?? [];
        $data[$type->value] = $value;
        $this->constraints = $data;
    }

    /**
     * Get constraint by enum.
     */
    public function getConstraint(ConstraintType $type)
    {
        return $this->constraints[$type->value] ?? null;
    }
}
