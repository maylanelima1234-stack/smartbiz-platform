<?php

namespace App\Domain\CRM\Services;

use App\Core\Audit\Facades\SmartAudit;
use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmComment;
use App\Domain\CRM\Models\CrmTag;
use App\Domain\CRM\Models\Lead;
use Illuminate\Support\Str;

class LeadEngagementService
{
    public function __construct(private readonly PlatformContext $context) {}

    public function addComment(Lead $lead, string $body): CrmComment
    {
        $this->ensureCompany($lead);

        $comment = CrmComment::query()->create([
            'public_id' => (string) Str::ulid(),
            'company_id' => $this->context->companyId(),
            'lead_id' => $lead->getKey(),
            'user_id' => auth()->id(),
            'body' => $body,
        ]);

        SmartAudit::log('crm.comment.created', $comment, [], ['lead_id' => $lead->getKey()]);

        return $comment->load('user');
    }

    public function syncTags(Lead $lead, array $tagIds): Lead
    {
        $this->ensureCompany($lead);

        $allowed = CrmTag::query()
            ->where('company_id', $this->context->companyId())
            ->whereIn('id', $tagIds)
            ->pluck('id')
            ->all();

        $before = $lead->tags()->pluck('crm_tags.id')->all();
        $lead->tags()->sync($allowed);

        SmartAudit::log('crm.lead.tags_synced', $lead, ['tags' => $before], ['tags' => $allowed]);

        return $lead->fresh('tags');
    }

    public function toggleFavorite(Lead $lead): Lead
    {
        $this->ensureCompany($lead);
        $before = $lead->is_favorite;
        $lead->update(['is_favorite' => ! $before]);

        SmartAudit::log('crm.lead.favorite_toggled', $lead, ['is_favorite' => $before], ['is_favorite' => $lead->is_favorite]);

        return $lead->fresh();
    }

    private function ensureCompany(Lead $lead): void
    {
        abort_unless((int) $lead->company_id === (int) $this->context->companyId(), 404);
    }
}
