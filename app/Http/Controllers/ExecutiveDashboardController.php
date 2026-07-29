<?php

namespace App\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmActivity;
use App\Domain\CRM\Models\Lead;
use App\Models\SmartWorkflow;
use App\Models\SmartWorkflowRun;
use Illuminate\View\View;

class ExecutiveDashboardController extends Controller
{
    public function __invoke(PlatformContext $context): View
    {
        $companyId = $context->companyId();
        abort_unless($companyId, 404, 'Selecione uma empresa ativa.');

        $base = Lead::query()->where('company_id', $companyId);
        $month = now()->startOfMonth();
        $previousMonth = now()->subMonthNoOverflow()->startOfMonth();
        $previousMonthEnd = now()->subMonthNoOverflow()->endOfMonth();

        $monthLeads = (clone $base)->where('created_at', '>=', $month)->count();
        $previousLeads = (clone $base)->whereBetween('created_at', [$previousMonth, $previousMonthEnd])->count();
        $growth = $previousLeads > 0 ? round((($monthLeads - $previousLeads) / $previousLeads) * 100, 1) : ($monthLeads > 0 ? 100 : 0);
        $won = (clone $base)->where('status', 'won')->count();
        $total = (clone $base)->count();
        $wonValue = (clone $base)->where('status', 'won')->sum('value');

        $bySource = (clone $base)->selectRaw('source, COUNT(*) as total, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as won', ['won'])
            ->groupBy('source')->orderByDesc('total')->get();
        $byOwner = (clone $base)->with('owner')->selectRaw('owner_id, COUNT(*) as total, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as won, SUM(value) as pipeline_value', ['won'])
            ->groupBy('owner_id')->orderByDesc('won')->get();

        return view('executive.index', [
            'activeCompany' => $context->company(),
            'metrics' => [
                'pipeline' => (float) (clone $base)->where('status', 'open')->sum('value'),
                'won_value' => (float) $wonValue,
                'ticket' => $won > 0 ? (float) $wonValue / $won : 0,
                'conversion' => $total > 0 ? round(($won / $total) * 100, 1) : 0,
                'month_leads' => $monthLeads,
                'growth' => $growth,
                'overdue' => CrmActivity::query()->where('company_id', $companyId)->whereNull('completed_at')->where('due_at', '<', now())->count(),
                'automations' => SmartWorkflow::query()->where('company_id', $companyId)->where('is_active', true)->count(),
                'automation_failures' => SmartWorkflowRun::query()->where('company_id', $companyId)->where('status', 'failed')->where('executed_at', '>=', now()->subDays(30))->count(),
            ],
            'bySource' => $bySource,
            'byOwner' => $byOwner,
        ]);
    }
}
