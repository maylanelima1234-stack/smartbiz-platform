<?php

namespace App\Core\Audit\DTO;

class AuditEntry
{
    public function __construct(
        public readonly string $event,
        public readonly ?string $auditableType = null,
        public readonly ?int $auditableId = null,
        public readonly array $oldValues = [],
        public readonly array $newValues = [],
        public readonly array $metadata = [],
    ) {
    }

    public static function make(
        string $event,
        ?object $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        array $metadata = [],
    ): self {
        return new self(
            event: $event,
            auditableType: $auditable ? $auditable::class : null,
            auditableId: method_exists($auditable, 'getKey')
                ? (int) $auditable->getKey()
                : null,
            oldValues: $oldValues,
            newValues: $newValues,
            metadata: $metadata,
        );
    }
}
