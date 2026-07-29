<?php

namespace App\Domain\CRM\Services;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmActivity;
use App\Domain\CRM\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CrmDashboardService
{
    public function __construct(private readonly PlatformContext $context) {}

    public function summary(): array
    {
        $companyId = $this->context->companyId();
        $base = Lead::query()->where('company_id', $companyId);

        $total = (clone $base)->count();
        $open = (clone $base)->where('status', 'open')->count();
        $won = (clone $base)->where('status', 'won')->count();
        $lost = (clone $base)->where('status', 'lost')->count();
        $wonValue = (float) (clone $base)->where('status', 'won')->sum('value');
        $openValue = (float) (clone $base)->where('status', 'open')->sum('value');
        $averageTicket = $won > 0 ? round($wonValue / $won, 2) : 0.0;
        $conversionRate = ($won + $lost) > 0 ? round(($won / ($won + $lost)) * 100, 2) : 0.0;

        return [
            'total' => $total,
            'today' => (clone $base)->whereDate('created_at', today())->count(),
            'week' => (clone $base)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => (clone $base)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'open' => $open,
            'won' => $won,
            'lost' => $lost,
            'won_value' => $wonValue,
            'open_value' => $openValue,
            'average_ticket' => $averageTicket,
            'conversion_rate' => $conversionRate,
            'overdue_count' => CrmActivity::query()->where('company_id', $companyId)->whereNull('completed_at')->where('due_at', '<', now())->count(),
            'recent_leads' => $this->recentLeads(),
            'source_stats' => $this->sourceStats(),
            'seller_ranking' => $this->sellerRanking(),
            'attention_items' => $this->attentionItems(),
            'due_today_count' => CrmActivity::query()->where('company_id', $companyId)->whereNull('completed_at')->whereDate('due_at', today())->count(),
            'stalled_count' => (clone $base)->where('status', 'open')->where('updated_at', '<', now()->subDays(3))->count(),
        ];
    }

    private function attentionItems(): Collection
    {
        $companyId = $this->context->companyId();

        $overdue = CrmActivity::query()
            ->where('company_id', $companyId)
            ->whereNull('completed_at')
            ->where('due_at', '<', now())
            ->with('lead')
            ->orderBy('due_at')
            ->limit(4)
            ->get()
            ->map(fn (CrmActivity $activity) => [
                'type' => 'overdue',
                'title' => $activity->title,
                'detail' => ($activity->lead?->name ?? 'Lead removido').' · vencida em '.optional($activity->due_at)->format('d/m H:i'),
                'url' => $activity->lead ? route('crm.leads.show', $activity->lead) : route('crm.agenda', ['period' => 'overdue']),
            ]);

        $stalled = Lead::query()
            ->where('company_id', $companyId)
            ->where('status', 'open')
            ->where('updated_at', '<', now()->subDays(3))
            ->with(['stage', 'owner'])
            ->oldest('updated_at')
            ->limit(4)
            ->get()
            ->map(fn (Lead $lead) => [
                'type' => 'stalled',
                'title' => $lead->name,
                'detail' => 'Sem atualização há '.$lead->updated_at->diffInDays(now()).' dias · '.($lead->stage?->name ?? 'Sem etapa'),
                'url' => route('crm.leads.show', $lead),
            ]);

        return $overdue->concat($stalled)->take(6)->values();
    }

    private function recentLeads(): Collection
    {
        return Lead::query()->where('company_id', $this->context->companyId())
            ->with(['stage', 'owner', 'tags'])->latest()->limit(8)->get();
    }

    private function sourceStats(): Collection
    {
        return Lead::query()->where('company_id', $this->context->companyId())
            ->selectRaw("coalesce(source, 'Não informada') as source, count(*) as total")
            ->groupBy('source')->orderByDesc('total')->limit(6)->get();
    }

    private function sellerRanking(): Collection
    {
        return Lead::query()->where('leads.company_id', $this->context->companyId())
            ->where('leads.status', 'won')->whereNotNull('owner_id')
            ->join('users', 'users.id', '=', 'leads.owner_id')
            ->select('users.name', DB::raw('count(leads.id) as deals'), DB::raw('sum(leads.value) as revenue'))
            ->groupBy('users.id', 'users.name')->orderByDesc('revenue')->limit(5)->get();
    }
}
