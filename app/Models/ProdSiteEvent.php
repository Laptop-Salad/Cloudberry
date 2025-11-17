<?php

namespace App\Models;

use App\Enums\ProductionSites\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdSiteEvent extends Model
{
    protected $guarded = ['id'];

    public $casts = [
        'type' => EventType::class,
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function productionSite(): BelongsTo {
        return $this->belongsTo(ProductionSite::class);
    }
}
