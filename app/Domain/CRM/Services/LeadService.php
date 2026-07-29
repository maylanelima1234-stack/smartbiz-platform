<?php

namespace App\Domain\CRM\Services;

use App\Core\Audit\Facades\SmartAudit;
use App\Core\Automation\WorkflowEngine;
use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmStage;
use App\Domain\CRM\Models\Lead;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class LeadService
{
    public function __construct(
        private readonly PlatformContext $context,
        private readonly ActivityService $activities,
        private readonly TimelineService $timeline,
        private readonly LeadHealthService $health,
        private readonly WorkflowEngine $workflows,
    ) {}

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
        $this->activities->system($lead, 'Lead criado', 'O lead foi cadastrado no CRM.');
        $this->timeline->record($lead, 'lead_created', 'Lead criado', 'O lead foi cadastrado no CRM.');
        $this->health->refresh($lead);
        $this->workflows->dispatch('lead.created', $lead);

        return $lead->load(['stage', 'owner']);
    }

    public function update(Lead $lead, array $data): Lead
    {
        $this->ensureCompany($lead);
        $before = $lead->getOriginal();
        $lead->update($data);

        $changes = $lead->getChanges();
        SmartAudit::log('crm.lead.updated', $lead, $before, $changes);
        $description = $this->describeChanges($before, $changes);
        $this->activities->system($lead, 'Lead atualizado', $description, ['changes' => array_keys($changes)]);
        $this->timeline->record($lead, 'lead_updated', 'Lead atualizado', $description, ['changes' => array_keys($changes)]);
        $this->health->refresh($lead);
        $this->workflows->dispatch('lead.updated', $lead, ['changes' => array_keys($changes)]);

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
        $lead->stage_entered_at = now();
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

        $fromStage = CrmStage::query()->find($before['stage_id']);

        SmartAudit::log(
            'crm.lead.moved',
            $lead,
            $before,
            ['stage_id' => $lead->stage_id, 'status' => $lead->status]
        );

        $this->activities->system(
            $lead,
            'Etapa alterada',
            sprintf('%s → %s', $fromStage?->name ?? 'Sem etapa', $stage->name),
            ['from_stage_id' => $before['stage_id'], 'to_stage_id' => $stage->getKey()]
        );
        $this->timeline->record($lead, 'stage_changed', 'Etapa alterada', sprintf('%s → %s', $fromStage?->name ?? 'Sem etapa', $stage->name));
        $this->health->refresh($lead);
        $this->workflows->dispatch('lead.moved', $lead, ['from_stage_id' => $before['stage_id'], 'to_stage_id' => $stage->getKey()]);

        return $lead->fresh(['stage', 'owner']);
    }

    public function delete(Lead $lead): void
    {
        $this->ensureCompany($lead);
        $lead->delete();
    }

    private function describeChanges(array $before, array $changes): string
    {
        $labels = [
            'owner_id' => 'responsável', 'stage_id' => 'etapa', 'status' => 'status',
            'priority' => 'prioridade', 'value' => 'valor', 'next_follow_up_at' => 'follow-up',
            'phone' => 'telefone', 'email' => 'e-mail', 'notes' => 'observações',
        ];

        $changed = collect(array_keys($changes))
            ->reject(fn (string $field) => in_array($field, ['updated_at'], true))
            ->map(fn (string $field) => $labels[$field] ?? $field)
            ->values();

        return $changed->isEmpty() ? 'Dados do lead atualizados.' : 'Campos alterados: '.$changed->implode(', ').'.';
    }

    private function ensureCompany(Lead $lead): void
    {
        abort_unless(
            (int) $lead->company_id === (int) $this->context->companyId(),
            404
        );
    }
}

