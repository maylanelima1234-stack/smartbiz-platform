<?php

namespace App\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Models\SmartWorkflowRun;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutomationRunController extends Controller
{
    public function __invoke(Request $request, PlatformContext $context): View
    {
        $status = $request->string('status')->toString();
        $runs = SmartWorkflowRun::query()
            ->where('company_id', $context->companyId())
            ->with('workflow')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('executed_at')->paginate(25)->withQueryString();
        return view('automations.runs', compact('runs', 'status'));
    }
}
