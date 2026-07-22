<?php

namespace App\Core\Licensing\Enums;

enum Limit: string
{
    case USERS = 'users';
    case COMPANIES = 'companies';
    case LEADS = 'leads';
    case CAMPAIGNS = 'campaigns';
    case STORAGE_MB = 'storage_mb';
    case AUTOMATIONS = 'automations';
}
