<?php

namespace App\Core\Authorization\Enums;

enum Permission: string
{
    case DASHBOARD_VIEW = 'dashboard.view';

    case COMPANIES_VIEW = 'companies.view';
    case COMPANIES_CREATE = 'companies.create';
    case COMPANIES_UPDATE = 'companies.update';
    case COMPANIES_DELETE = 'companies.delete';
    case COMPANIES_MANAGE = 'companies.manage';

    case USERS_VIEW = 'users.view';
    case USERS_CREATE = 'users.create';
    case USERS_UPDATE = 'users.update';
    case USERS_DELETE = 'users.delete';
    case USERS_MANAGE = 'users.manage';

    case CRM_VIEW = 'crm.view';
    case CRM_CREATE = 'crm.create';
    case CRM_UPDATE = 'crm.update';
    case CRM_DELETE = 'crm.delete';
    case CRM_MANAGE = 'crm.manage';
}
