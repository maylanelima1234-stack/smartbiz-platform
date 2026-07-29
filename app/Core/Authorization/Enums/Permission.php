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

    case MARKETING_VIEW = 'marketing.view';
    case MARKETING_MANAGE = 'marketing.manage';

    case ATTENDANCES_VIEW = 'attendances.view';
    case ATTENDANCES_TAKEOVER = 'attendances.takeover';
    case ATTENDANCES_TRANSFER = 'attendances.transfer';
    case ATTENDANCES_VIEW_RAQUEL_PROGRESS = 'attendances.view_raquel_progress';

    case REPORTS_VIEW = 'reports.view';
    case SUPPORT_VIEW = 'support.view';
    case AGENDA_VIEW = 'agenda.view';

    case FINANCE_DASHBOARD_VIEW = 'finance.dashboard.view';
    case FINANCE_INVOICES_VIEW = 'finance.invoices.view';
    case FINANCE_INVOICES_CREATE = 'finance.invoices.create';
    case FINANCE_PAYMENTS_VIEW = 'finance.payments.view';
    case FINANCE_PAYMENTS_MANAGE = 'finance.payments.manage';
    case FINANCE_PLANS_VIEW = 'finance.plans.view';
    case FINANCE_PLANS_EDIT = 'finance.plans.edit';
    case FINANCE_SUBSCRIPTIONS_VIEW = 'finance.subscriptions.view';
    case FINANCE_REPORTS_VIEW = 'finance.reports.view';

    case SMARTBOT_MANAGE = 'smartbot.manage';
    case MARKETPLACE_VIEW = 'marketplace.view';
}
