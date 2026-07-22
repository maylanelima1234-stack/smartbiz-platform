<?php

namespace App\Domain\CRM\Services;

use App\Core\Audit\Facades\SmartAudit;
use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmStage;
use App\Domain\CRM\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class LeadService
{
    public function __construct(private readonly PlatformContext $context) {}

    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return Lead::query()
            ->where('company_id', $this->context->companyId())
            ->with(['stage', 'owner'])
            ->when($filters['search'] ?? null, function ($query, $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($filters['stage_id'] ?? null, fn ($q, $v) => $q->where('stage_id', $v))
            ->when($filters['owner_id'] ?? null, fn ($q, $v) => $q->where('owner_id', $v))
            ->latest()
            ->paginate((int) ($filters['per_page'] ?? 20));
    }

    public function create(array $data): Lead
    {
        $data['public_id'] = (string) Str::ulid();
        $data['company_id'] = $this->context->companyId();

        $lead = Lead::query()->create($data);

        SmartAudit::log('crm.lead.created', $lead, [], $lead->getAttributes());

        return $lead->load(['stage', 'owner']);
    }

    public function update(Lead $lead, array $data): Lead
    {
        $this->ensureCompany($lead);
        $before = $lead->getOriginal();
        $lead->update($data);

        SmartAudit::log('crm.lead.updated', $lead, $before, $lead->getChanges());

        return $lead->fresh(['stage', 'owner']);
    }

    public function move(Lead $lead, CrmStage $stage): Lead
    {
        $this->ensureCompany($lead);

        abort_unless(
            (int) $stage->pipeline?->company_id === (int) $this->context->companyId(),
            404
        );

        $before = ['stage_id' => $lead->stage_id, 'status' => $lead->status];

        $lead->stage_id = $stage->getKey();
        $lead->pipeline_id = $stage->pipeline_id;

        if ($stage->is_won) {
            $lead->status = 'won';
            $lead->won_at = now();
            $lead->lost_at = null;
        } elseif ($stage->is_lost) {
            $lead->status = 'lost';
            $lead->lost_at = now();
            $lead->won_at = null;
        } else {
            $lead->status = 'open';
        }

        $lead->save();

        SmartAudit::log(
            'crm.lead.moved',
            $lead,
            $before,
            ['stage_id' => $lead->stage_id, 'status' => $lead->status]
        );

        return $lead->fresh(['stage', 'owner']);
    }

    public function delete(Lead $lead): void
    {
        $this->ensureCompany($lead);
        $lead->delete();
    }

    private function ensureCompany(Lead $lead): void
    {
        abort_unless(
            (int) $lead->company_id === (int) $this->context->companyId(),
            404
        );
    }
}

