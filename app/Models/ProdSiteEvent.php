<?php

namespace App\Models;

use App\Enums\ProductionSites\EventType;
use Illuminate\Database\Eloquent\Model;

class ProdSiteEvent extends Model
{
    protected $guarded = ['id'];

    public $casts = [
        'type' => EventType::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];
}
