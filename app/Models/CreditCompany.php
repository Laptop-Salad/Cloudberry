<?php

namespace App\Models;

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
        'constraints'
    ];

    /**
     * Get the relationships.
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
}
