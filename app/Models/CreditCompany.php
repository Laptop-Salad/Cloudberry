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
        'constraints_on_storage_method',
        'constraints_on_co2_source'
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
        ];
    }
}
