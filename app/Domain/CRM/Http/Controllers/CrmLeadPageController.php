<?php

namespace App\Domain\CRM\Http\Controllers;

use App\Core\Authorization\Enums\Permission;
use App\Core\Authorization\Facades\SmartGate;
use App\Core\Context\PlatformContext;
use App\Core\Licensing\Enums\Feature;
use App\Core\Licensing\Facades\SmartLicense;
use App\Domain\CRM\Http\Requests\StoreLeadRequest;
use App\Domain\CRM\Http\Requests\UpdateLeadRequest;
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
    ) {}

    public function index(Request $request): View
    {
        $this->authorizeView();

        $filters = $request->only(['search', 'status', 'priority', 'source', 'favorite']);
        $companyId = $this->context->companyId();

        $leads = Lead::query()
            ->where('company_id', $companyId)
            ->with(['stage', 'owner', 'tags'])
            ->when($filters['search'] ?? null, fn ($q, $v) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$v}%")
                ->orWhere('email', 'like', "%{$v}%")
                ->orWhere('phone', 'like', "%{$v}%")))
            ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($filters['priority'] ?? null, fn ($q, $v) => $q->where('priority', $v))
            ->when($filters['source'] ?? null, fn ($q, $v) => $q->where('source', $v))
            ->when(($filters['favorite'] ?? null) === '1', fn ($q) => $q->where('is_favorite', true))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $sources = Lead::query()
            ->where('company_id', $companyId)
            ->whereNotNull('source')
            ->distinct()
            ->orderBy('source')
            ->pluck('source');

        return view('crm.leads.index', compact('leads', 'filters', 'sources'));
    }

    public function create(): View
    {
        $this->authorizeUpdate();

        return view('crm.leads.create', $this->formData());
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $this->authorizeUpdate();
        $lead = $this->service->create($request->validated());

        return redirect()->route('crm.leads.show', $lead)
            ->with('status', 'lead-created');
    }

    public function show(Lead $lead): View
    {
        $this->authorizeView();
        $this->ensureCompany($lead);

        return view('crm.leads.show', [
            'lead' => $lead->load(['pipeline', 'stage', 'owner', 'activities.user', 'comments.user', 'tags']),
            'availableTags' => CrmTag::query()
                ->where('company_id', $this->context->companyId())
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function edit(Lead $lead): View
    {
        $this->authorizeUpdate();
        $this->ensureCompany($lead);

        return view('crm.leads.edit', array_merge($this->formData(), compact('lead')));
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorizeUpdate();
        $this->service->update($lead, $request->validated());

        return redirect()->route('crm.leads.show', $lead)
            ->with('status', 'lead-updated');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->authorizeUpdate();
        $this->service->delete($lead);

        return redirect()->route('crm.leads.index')
            ->with('status', 'lead-deleted');
    }

    public function kanban(): View
    {
        $this->authorizeView();

        $pipelines = CrmPipeline::query()
            ->where('company_id', $this->context->companyId())
            ->where('status', 'active')
            ->with(['stages' => fn ($q) => $q
                ->where('status', 'active')
                ->with(['leads' => fn ($q) => $q
                    ->where('company_id', $this->context->companyId())
                    ->with(['owner', 'tags'])
                    ->latest()])
                ->orderBy('position')])
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();

        return view('crm.kanban', compact('pipelines'));
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
                ->whereHas('pipeline', fn ($q) => $q->where('company_id', $companyId))
                ->where('status', 'active')
                ->orderBy('position')
                ->get(),
            'users' => User::query()->orderBy('name')->get(),
        ];
    }

    private function authorizeView(): void
    {
        abort_unless(SmartLicense::allows(Feature::CRM), 403);
        SmartGate::authorize(Permission::CRM_VIEW);
    }

    private function authorizeUpdate(): void
    {
        abort_unless(SmartLicense::allows(Feature::CRM), 403);
        SmartGate::authorize(Permission::CRM_UPDATE);
    }

    private function ensureCompany(Lead $lead): void
    {
        abort_unless((int) $lead->company_id === (int) $this->context->companyId(), 404);
    }
}
