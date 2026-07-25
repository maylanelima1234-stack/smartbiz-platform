<?php

return [
    App\Providers\AppServiceProvider::class,

    App\Core\Authorization\Providers\AuthorizationServiceProvider::class,
    App\Core\Audit\Providers\AuditServiceProvider::class,
    App\Core\Context\Providers\PlatformContextServiceProvider::class,
    App\Core\Licensing\Providers\LicensingServiceProvider::class,

    App\Domain\CRM\Providers\CrmServiceProvider::class,
];