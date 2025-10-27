<?php

namespace App\Enums;

enum ConstraintType: int
{
    case NONE = 0;
    case ACCEPTS_CO2_FROM_FOOD_GRADE =1;
    case ACCEPTS_CO2_FROM_BIOGAS_NON_MANURE = 2;
    case MUST_BE_DISTILLERY_SOURCE = 3;
    case MUST_BE_CARBONATION_OCO = 4;
}