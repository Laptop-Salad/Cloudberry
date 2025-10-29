<?php

namespace App\Enums;

enum ConstraintType: int
{
    case NONE = 0;

    //Delivery Companies table constraints
    case ACCEPTS_CO2_FROM_CMA_FULLY_TESTED =1;
    case ACCEPTS_CO2_FROM_LL_FULLY_TESTED =2;
    case ACCEPTS_CO2_FROM_BIOGAS_NON_MANURE = 3;
    case SEE_CREDIT_COMPANY_CONSTRAINTS = 4;

    //Credit Companies table constraints
    case MUST_BE_DISTILLERY_SOURCE = 5;
    case MUST_BE_CARBONATION_OCO = 6;
}