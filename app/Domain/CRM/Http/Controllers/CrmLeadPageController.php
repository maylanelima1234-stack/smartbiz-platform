<?php

namespace App\Domain\CRM\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Http\Requests\StoreLeadRequest;
use App\Domain\CRM\Http\Requests\UpdateLeadRequest;
use App\Domain\CRM\Enums\LeadPriority;
use App\Domain\CRM\Enums\LeadSource;
use App\Domain\CRM\Enums\LeadStatus;
use App\Domain\CRM\Models\CrmPipeline;
use App\Domain\CRM\Models\CrmStage;
use App\Domain\CRM\Models\CrmTag;
use App\Domain\CRM\Models\Lead;
use App\Domain\CRM\Services\LeadService;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmLeadPageController extends Controller
{
    public function __construct(
        private readonly PlatformContext $context,
        private readonly LeadService $service,
        private readonly \App\Domain\CRM\Services\LeadHealthService $health,
    ) {
    }

    public function index(Request $request): View
    {
        $filters = $request->only([
            'search',
            'status',
            'priority',
            'source',
            'favorite',
            'owner',
            'mine',
        ]);

        $companyId = $this->context->companyId();

        $leads = Lead::query()
            ->where('company_id', $companyId)
            ->with(['stage', 'owner', 'tags'])
            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where(
                fn ($searchQuery) => $searchQuery
                    ->where('name', 'like', "%{$value}%")
                    ->orWhere('email', 'like', "%{$value}%")
                    ->orWhere('phone', 'like', "%{$value}%")
            ))
            ->when($filters['status'] ?? null, fn ($query, $value) => $query->where('status', $value))
            ->when($filters['priority'] ?? null, fn ($query, $value) => $query->where('priority', $value))
            ->when($filters['source'] ?? null, fn ($query, $value) => $query->where('source', $value))
            ->when(($filters['favorite'] ?? null) === '1', fn ($query) => $query->where('is_favorite', true))
            ->when($filters['owner'] ?? null, fn ($query, $value) => $query->where('owner_id', $value))
            ->when(($filters['mine'] ?? null) === '1', fn ($query) => $query->where('owner_id', auth()->id()))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $sources = Lead::query()
            ->where('company_id', $companyId)
            ->whereNotNull('source')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');

        $owners = $this->companyUsers($companyId);

        return view('crm.leads.index', compact('leads', 'filters', 'sources', 'owners'));
    }

    public function create(): View
    {
        return view('crm.leads.create', $this->formData());
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $lead = $this->service->create($request->validated());

        return redirect()
            ->route('crm.leads.show', $lead)
            ->with('status', 'lead-created');
    }

    public function show(Lead $lead): View
    {
        $this->ensureCompany($lead);
        $this->health->refresh($lead);

        return view('crm.leads.show', [
            'lead' => $lead->load([
                'pipeline',
                'stage',
                'owner',
                'activities.user',
                'comments.user',
                'timelineEvents.user',
                'files.uploader',
                'tags',
            ]),
            'availableTags' => CrmTag::query()
                ->where('company_id', $this->context->companyId())
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function edit(Lead $lead): View
    {
        $this->ensureCompany($lead);

        return view('crm.leads.edit', array_merge(
            $this->formData(),
            compact('lead')
        ));
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->ensureCompany($lead);
        $this->service->update($lead, $request->validated());

        return redirect()
            ->route('crm.leads.show', $lead)
            ->with('status', 'lead-updated');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->ensureCompany($lead);
        $this->service->delete($lead);

        return redirect()
            ->route('crm.leads.index')
            ->with('status', 'lead-deleted');
    }

    public function kanban(Request $request): View
    {
        $companyId = $this->context->companyId();
        $filters = $request->only(['search', 'priority', 'owner', 'favorite', 'mine']);

        $pipelines = CrmPipeline::query()
            ->where('company_id', $companyId)
            ->where('status', 'active')
            ->with([
                'stages' => fn ($query) => $query
                    ->where('status', 'active')
                    ->with([
                        'leads' => fn ($leadQuery) => $leadQuery
                            ->where('company_id', $companyId)
                            ->with(['owner', 'tags'])
                            ->when($filters['search'] ?? null, fn ($query, $value) => $query->where(
                                fn ($searchQuery) => $searchQuery
                                    ->where('name', 'like', "%{$value}%")
                                    ->orWhere('email', 'like', "%{$value}%")
                                    ->orWhere('phone', 'like', "%{$value}%")
                            ))
                            ->when($filters['priority'] ?? null, fn ($query, $value) => $query->where('priority', $value))
                            ->when($filters['owner'] ?? null, fn ($query, $value) => $query->where('owner_id', $value))
                            ->when(($filters['favorite'] ?? null) === '1', fn ($query) => $query->where('is_favorite', true))
                            ->when(($filters['mine'] ?? null) === '1', fn ($query) => $query->where('owner_id', auth()->id()))
                            ->latest(),
                    ])
                    ->orderBy('position'),
            ])
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        $owners = $this->companyUsers($companyId);

        return view('crm.kanban', compact('pipelines', 'filters', 'owners'));
    }

    private function formData(): array
    {
        $companyId = $this->context->companyId();

        return [
            'pipelines' => CrmPipeline::query()
                ->where('company_id', $companyId)
                ->where('status', 'active')
                ->orderByDesc('is_default')
                ->orderBy('name')
                ->get(),
            'stages' => CrmStage::query()
                ->whereHas(
                    'pipeline',
                    fn ($query) => $query->where('company_id', $companyId)
                )
                ->where('status', 'active')
                ->orderBy('position')
                ->get(),
            'users' => $this->companyUsers($companyId),
            'sourceOptions' => LeadSource::options(),
            'priorityOptions' => LeadPriority::options(),
            'statusOptions' => LeadStatus::options(),
        ];
    }

    private function companyUsers(?int $companyId)
    {
        return User::query()
            ->where('status', 'Ativo')
            ->where(function ($query) use ($companyId): void {
                $query
                    ->where('company_id', $companyId)
                    ->orWhereHas(
                        'companies',
                        fn ($membership) => $membership
                            ->where('companies.id', $companyId)
                            ->where('company_user.status', 'active')
                    );
            })
            ->orderBy('name')
            ->get(['users.id', 'users.name']);
    }

    private function ensureCompany(Lead $lead): void
    {
        abort_unless(
            (int) $lead->company_id === (int) $this->context->companyId(),
            404
        );
    }
}
