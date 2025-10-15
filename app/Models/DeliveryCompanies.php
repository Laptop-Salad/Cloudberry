<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryCompanies extends Model
{
    protected $fillable = ['co2_delivery_obligations', 'location', 'type', 'cod', 'annual_min_obligation', 'annual_max_obligation', 'weekly_min', 'weekly_max', 'buffer_tank_size', 'constraints'];
}
