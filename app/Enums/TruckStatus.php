<?php

namespace App\Enums;

enum TruckStatus: int
{
    use HasDisplay;

    case AVAILABLE = 0;
    case IN_TRANSIT = 1;
    case IN_MAINTENANCE = 2;
    case AWAITING_MAINTENANCE = 3;

    public function colour(): string
    {
        return match ($this) {
            self::AVAILABLE => 'bg-green-200 text-green-800 border-green-800',
            self::IN_TRANSIT => 'bg-yellow-200 text-yellow-800 border-yellow-800',
            self::IN_MAINTENANCE => 'text-red-200 text-red-800 border-red-800',
            self::AWAITING_MAINTENANCE => 'text-orange-200 text-orange-800 border-orange-800',
        };
    }
}
