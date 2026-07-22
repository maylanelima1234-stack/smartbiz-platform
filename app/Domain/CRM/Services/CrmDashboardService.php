<?php

namespace App\Domain\CRM\Services;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmPipeline;
use App\Domain\CRM\Models\Lead;
use Illuminate\Support\Collection;

class CrmDashboardService
{
    public function __construct(
        private readonly PlatformContext $context,
    ) {
    }

    public function summary(): array
    {
        $companyId = $this->context->companyId();

        $baseQuery = Lead::query()
            ->where('company_id', $companyId);

        $total = (clone $baseQuery)->count();
        $open = (clone $baseQuery)
            ->where('status', 'open')
            ->count();

        $won = (clone $baseQuery)
            ->where('status', 'won')
            ->count();

        $lost = (clone $baseQuery)
            ->where('status', 'lost')
            ->count();

        $wonValue = (float) (clone $baseQuery)
            ->where('status', 'won')
            ->sum('value');

        $conversionRate = $total > 0
            ? round(($won / $total) * 100, 2)
            : 0.0;

        return [
            'total' => $total,
            'open' => $open,
            'won' => $won,
            'lost' => $lost,
            'won_value' => $wonValue,
            'conversion_rate' => $conversionRate,
            'recent_leads' => $this->recentLeads(),
            'pipelines' => $this->pipelines(),
        ];
    }

    private function recentLeads(): Collection
    {
        return Lead::query()
            ->where('company_id', $this->context->companyId())
            ->with([
                'stage',
                'owner',
            ])
            ->latest()
            ->limit(8)
            ->get();
    }

    private function pipelines(): Collection
    {
        return CrmPipeline::query()
            ->where('company_id', $this->context->companyId())
            ->where('status', 'active')
            ->with([
                'stages' => function ($query): void {
                    $query
                        ->where('status', 'active')
                        ->with([
                            'leads' => function ($query): void {
                                $query
                                    ->where(
                                        'company_id',
                                        $this->context->companyId()
                                    )
                                    ->with('owner')
                                    ->latest();
                            },
                        ])
                        ->orderBy('position');
                },
            ])
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();
    }
}
