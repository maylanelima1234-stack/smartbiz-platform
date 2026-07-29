<?php

namespace App\Core\Audit\Services;

use App\Core\Audit\DTO\AuditEntry;
use App\Models\SmartAuditLog;

class SmartAudit
{
    public function __construct(
        private readonly AuditService $audit,
    ) {
    }

    public function record(AuditEntry $entry): SmartAuditLog
    {
        return $this->audit->record($entry);
    }

    public function log(
        string $event,
        ?object $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = [],
    ): SmartAuditLog {
        return $this->audit->log(
            event: $event,
            auditable: $auditable,
            oldValues: $oldValues,
            newValues: $newValues,
            metadata: $metadata,
        );
    }
}
