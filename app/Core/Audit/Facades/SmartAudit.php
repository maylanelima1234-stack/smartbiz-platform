<?php

namespace App\Core\Audit\Facades;

use App\Core\Audit\Services\SmartAudit as SmartAuditService;
use Illuminate\Support\Facades\Facade;

class SmartAudit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SmartAuditService::class;
    }
}
