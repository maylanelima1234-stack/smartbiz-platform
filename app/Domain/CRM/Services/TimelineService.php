<?php

namespace App\Domain\CRM\Services;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmTimelineEvent;
use App\Domain\CRM\Models\Lead;
use Illuminate\Support\Str;

class TimelineService
{
    public function __construct(private readonly PlatformContext $context) {}

    public function record(Lead $lead, string $type, string $title, ?string $description = null, array $metadata = []): CrmTimelineEvent
    {
        abort_unless((int) $lead->company_id === (int) $this->context->companyId(), 404);

        return CrmTimelineEvent::query()->create([
            'public_id' => (string) Str::ulid(),
            'company_id' => $this->context->companyId(),
            'lead_id' => $lead->getKey(),
            'user_id' => auth()->id(),
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata ?: null,
        ]);
    }
}
