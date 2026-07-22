<?php

namespace App\Core\Audit\Services;

use App\Core\Audit\Contracts\AuditRepositoryContract;
use App\Core\Audit\DTO\AuditEntry;
use App\Core\Context\PlatformContext;
use App\Models\SmartAuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuditService
{
    public function __construct(
        private readonly PlatformContext $context,
        private readonly AuditRepositoryContract $repository,
    ) {
    }

    public function record(AuditEntry $entry): SmartAuditLog
    {
        $request = request();

        return $this->repository->create([
            'public_id' => (string) Str::ulid(),
            'company_id' => $this->context->companyId(),
            'user_id' => Auth::id(),
            'event' => $entry->event,
            'auditable_type' => $entry->auditableType,
            'auditable_id' => $entry->auditableId,
            'old_values' => $entry->oldValues ?: null,
            'new_values' => $entry->newValues ?: null,
            'metadata' => $entry->metadata ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'occurred_at' => now(),
        ]);
    }

    public function log(
        string $event,
        ?object $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = [],
    ): SmartAuditLog {
        return $this->record(
            AuditEntry::make(
                event: $event,
                auditable: $auditable,
                oldValues: $oldValues,
                newValues: $newValues,
                metadata: $metadata,
            )
        );
    }
}
