<?php

namespace App\Enums;

enum TruckStatus: int
{
    use HasDisplay;

    case AVAILABLE = 0;
    case IN_TRANSIT = 1;
    case IN_MAINTENANCE = 2;
    case AWAITING_MAINTENANCE = 3;
}
