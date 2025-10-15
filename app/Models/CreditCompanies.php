<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCompanies extends Model
{
    protected $fillable = ['cdr_credit_customer', 'credits_purchased', 'lca', 'co2_required', 'target_delivery_year', 'constraints_on_storage_method', 'constraints_on_co2_source'];
}
