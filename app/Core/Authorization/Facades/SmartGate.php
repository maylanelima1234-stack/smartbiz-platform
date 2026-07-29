<?php

namespace App\Core\Authorization\Facades;

use App\Core\Authorization\Services\SmartGate as SmartGateService;
use Illuminate\Support\Facades\Facade;

class SmartGate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SmartGateService::class;
    }
}
