<?php

namespace App\Enums;

enum TruckStatus: int
{
    case AVAILABLE = 0;
    case IN_TRANSIT = 1;
}
