<?php

namespace App\Domain\CRM\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmPipeline;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PipelineController extends Controller
{
    public function __construct(
        private readonly PlatformContext $context
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json(
            CrmPipeline::query()
                ->where('company_id', $this->context->companyId())
                ->with([
                    'stages' => fn ($query) => $query->withCount('leads'),
                ])
                ->where('status', 'active')
                ->orderByDesc('is_default')
                ->orderBy('name')
                ->get()
        );
    }
}
