<?php

namespace App\Enums;

enum RoleType: string
{
    case ADMIN = 'Admin';
    case OPERATIONS_MANAGER = 'Operations Manager';
    case DATA_ANALYST = 'Data Analyst';
}
