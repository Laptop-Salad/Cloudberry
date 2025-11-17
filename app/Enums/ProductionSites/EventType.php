<?php

namespace App\Enums\ProductionSites;

use App\Enums\HasDisplay;

enum EventType: int
{
    use HasDisplay;

    case SHUTDOWN = 0;
}
