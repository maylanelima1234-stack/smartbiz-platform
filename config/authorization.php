<?php

return [
    'super_roles' => [
        'super_admin',
        'admin_cto',
    ],

    'cache_ttl_seconds' => 300,

    /*
    |--------------------------------------------------------------------------
    | Perfis protegidos da plataforma
    |--------------------------------------------------------------------------
    | O perfil admin_tm começa sem acesso financeiro. O Adm. CTO poderá
    | conceder permissões individuais posteriormente em user_permissions.
    */
    'protected_roles' => [
        'admin_tm' => [
            'deny_by_default' => [
                'finance.dashboard.view',
                'finance.invoices.view',
                'finance.invoices.create',
                'finance.payments.view',
                'finance.payments.manage',
                'finance.plans.view',
                'finance.plans.edit',
                'finance.subscriptions.view',
                'finance.reports.view',
                'smartbot.manage',
                'marketplace.view',
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'users.manage',
            ],
        ],
    ],
];
