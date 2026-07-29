<?php

return [
    'default_plan' => 'starter',

    'cache_ttl_seconds' => 300,

    'plans' => [
        'starter' => [
            'features' => [
                'dashboard',
                'companies',
                'users',
                'crm',
                'support',
            ],
            'limits' => [
                'users' => 5,
                'companies' => 1,
                'leads' => 500,
                'campaigns' => 5,
                'storage_mb' => 1024,
                'automations' => 10,
            ],
        ],

        'pro' => [
            'features' => [
                'dashboard',
                'companies',
                'users',
                'crm',
                'marketing',
                'finance',
                'support',
                'reports',
                'api',
            ],
            'limits' => [
                'users' => 25,
                'companies' => 5,
                'leads' => 10000,
                'campaigns' => 50,
                'storage_mb' => 10240,
                'automations' => 100,
            ],
        ],

        'enterprise' => [
            'features' => [
                'dashboard',
                'companies',
                'users',
                'crm',
                'marketing',
                'finance',
                'support',
                'smartbot',
                'reports',
                'api',
                'ai',
            ],
            'limits' => [
                'users' => 'unlimited',
                'companies' => 'unlimited',
                'leads' => 'unlimited',
                'campaigns' => 'unlimited',
                'storage_mb' => 'unlimited',
                'automations' => 'unlimited',
            ],
        ],
    ],
];
