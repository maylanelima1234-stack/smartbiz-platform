<?php

namespace App\Domain\CRM\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmActivity;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrmAgendaController extends Controller
{
    public function __construct(
        private readonly PlatformContext $context
    ) {
    }

    public function __invoke(Request $request): View
    {
        $period = $request->string('period', 'upcoming')->toString();

        $activities = CrmActivity::query()
            ->where('company_id', $this->context->companyId())
            ->with(['lead', 'user'])
            ->whereNull('completed_at')
            ->when($period === 'overdue', fn ($query) => $query->where('due_at', '<', now()))
            ->when($period === 'today', fn ($query) => $query->whereDate('due_at', today()))
            ->when($period === 'upcoming', fn ($query) => $query->where('due_at', '>=', now()))
            ->orderByRaw('due_at is null')
            ->orderBy('due_at')
            ->paginate(30)
            ->withQueryString();

        return view('crm.agenda', compact('activities', 'period'));
    }
}
