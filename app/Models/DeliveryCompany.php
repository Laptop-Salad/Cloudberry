<?php

namespace App\Models;

use App\Enums\ConstraintType;
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
        'name',
        'location',
        'type', 'cod',
        'annual_min_obligation',
        'annual_max_obligation',
        'weekly_min',
        'weekly_max',
        'buffer_tank_size',
        'constraints',
        'credit_company_id',
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
            'constraints' => 'array',
        ];
    }

    /**
     * Get the relationships.
     */
    public function creditCompanies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(CreditCompany::class);
    }

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
            if (is_int($value)) {
                $type = ConstraintType::tryFrom($value);
                $readable[$key] = $type?->name ?? "Unknown";
            } else {
                $readable[$key] = $value;
            }
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

    protected static function booted()
    {
        static::created(function ($deliveryCompany) {
            if (($deliveryCompany->constraints['delivery_condition'] ?? null) === ConstraintType::SEE_CREDIT_COMPANY_CONSTRAINTS->value) {
                $deliveryCompany->creditCompanies()->syncWithoutDetaching(CreditCompany::all()->pluck('id'));
            }
        });
    }
}
