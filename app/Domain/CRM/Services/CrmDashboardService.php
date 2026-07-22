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
        ];
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
