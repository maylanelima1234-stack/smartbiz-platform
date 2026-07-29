<?php

namespace App\Core\Audit\Repositories;

use App\Core\Audit\Contracts\AuditRepositoryContract;
use App\Models\SmartAuditLog;

class EloquentAuditRepository implements AuditRepositoryContract
{
    public function create(array $data): SmartAuditLog
    {
        return SmartAuditLog::query()->create($data);
    }
}
