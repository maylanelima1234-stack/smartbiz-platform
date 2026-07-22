<?php

namespace App\Core\Licensing\Enums;

enum Feature: string
{
    case DASHBOARD = 'dashboard';
    case COMPANIES = 'companies';
    case USERS = 'users';
    case CRM = 'crm';
    case MARKETING = 'marketing';
    case FINANCE = 'finance';
    case SUPPORT = 'support';
    case SMARTBOT = 'smartbot';
    case REPORTS = 'reports';
    case API = 'api';
    case AI = 'ai';
}
