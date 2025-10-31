<?php

namespace App\Enums;

enum PermissionType: string
{
    //General management
    case MANAGE_USERS = 'manage users';
    case VIEW_USERS = 'view users';

    //Operations
    case MANAGE_DELIVERIES = 'manage deliveries';
    case VIEW_DELIVERIES = 'view deliveries';
    case MANAGE_PRODUCTION_SITES = 'manage production sites';
    case VIEW_PRODUCTION_SITES = 'view production sites';

    //Analytics
    case VIEW_ANALYTICS = 'view analytics';
    case EXPORT_DATA = 'export data';
}
