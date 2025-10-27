<?php

namespace App\Models;

use App\Enums\ConstraintType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCompany extends Model
{
    /** @use HasFactory<\Database\Factories\CreditCompanyFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'cdr_credit_customer',
        'credits_purchased',
        'lca',
        'co2_required',
        'target_delivery_year',
        'constraints',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'credits_purchased' => 'float',
            'lca' => 'float',
            'co2_required' => 'float',
            'target_delivery_year' => 'float',
            'constraints' => 'array',
        ];
    }

    /**
     * Get the relationships.
     */
    public function deliveryCompanies()
    {
        return $this->hasMany(DeliveryCompany::class);
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
