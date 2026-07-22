<?php

namespace App\Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Http\Requests\StoreLeadRequest;
use App\Modules\CRM\Http\Requests\UpdateLeadRequest;
use App\Modules\CRM\Models\CrmStage;
use App\Modules\CRM\Models\Lead;
use App\Modules\CRM\Services\LeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(private readonly LeadService $service) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            $this->service->paginate($request->only([
                'search', 'stage_id', 'owner_id', 'per_page',
            ]))
        );
    }

    public function store(StoreLeadRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->create($request->validated()),
            201
        );
    }

    public function show(Lead $lead): JsonResponse
    {
        return response()->json(
            $lead->load(['pipeline', 'stage', 'owner', 'activities.user'])
        );
    }

    public function update(UpdateLeadRequest $request, Lead $lead): JsonResponse
    {
        return response()->json(
            $this->service->update($lead, $request->validated())
        );
    }

    public function move(Request $request, Lead $lead): JsonResponse
    {
        $data = $request->validate(['stage_id' => ['required', 'exists:crm_stages,id']]);

        return response()->json(
            $this->service->move($lead, CrmStage::query()->findOrFail($data['stage_id']))
        );
    }

    public function destroy(Lead $lead): JsonResponse
    {
        $this->service->delete($lead);
        return response()->json(status: 204);
    }
}
