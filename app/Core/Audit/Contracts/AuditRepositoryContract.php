<?php

namespace App\Core\Audit\Contracts;

use App\Models\SmartAuditLog;

interface AuditRepositoryContract
{
    public function create(array $data): SmartAuditLog;
}
