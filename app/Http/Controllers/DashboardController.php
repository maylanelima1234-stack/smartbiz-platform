<?php

namespace App\Http\Controllers;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Models\CrmActivity;
use App\Domain\CRM\Models\CrmTimelineEvent;
use App\Domain\CRM\Models\Lead;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(PlatformContext $context): View
    {
        $user = auth()->user();
        $isCto = $user?->hasGlobalAdministrationScope() ?? false;
        $isInternal = $user?->isInternalSmartBizUser() ?? false;
        $activeCompany = $context->company();
        $companyId = $activeCompany?->getKey();

        $companyQuery = Company::query();
        $leadQuery = Lead::query();
        $activityQuery = CrmActivity::query();
        $timelineQuery = CrmTimelineEvent::query();

        if ($companyId) {
            $leadQuery->where('company_id', $companyId);
            $activityQuery->where('company_id', $companyId);
            $timelineQuery->where('company_id', $companyId);
        } elseif (! $isInternal) {
            $companyQuery->whereRaw('1 = 0');
            $leadQuery->whereRaw('1 = 0');
            $activityQuery->whereRaw('1 = 0');
            $timelineQuery->whereRaw('1 = 0');
        }

        if (! $isInternal && $companyId) {
            $companyQuery->whereKey($companyId);
        }

        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $staleLimit = now()->subDays(3);

        $totalLeads = (clone $leadQuery)->count();
        $wonLeads = (clone $leadQuery)->whereNotNull('won_at')->count();
        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;

        $metrics = [
            'companies' => (clone $companyQuery)->count(),
            'active_companies' => (clone $companyQuery)->whereIn('status', ['Ativa', 'active'])->count(),
            'users' => $isCto ? User::query()->count() : null,
            'leads' => $totalLeads,
            'pipeline_value' => 'R$ '.number_format((float) (clone $leadQuery)->sum('value'), 2, ',', '.'),
            'follow_ups' => (clone $leadQuery)->whereNotNull('next_follow_up_at')->where('next_follow_up_at', '<=', now()->addDays(7))->count(),
            'won' => $wonLeads,
            'conversion_rate' => number_format($conversionRate, 1, ',', '.').'%',
            'overdue_activities' => (clone $activityQuery)->whereNull('completed_at')->where('due_at', '<', $todayStart)->count(),
            'today_activities' => (clone $activityQuery)->whereNull('completed_at')->whereBetween('due_at', [$todayStart, $todayEnd])->count(),
        ];

        $attentionItems = collect();

        $overdueActivities = (clone $activityQuery)
            ->with(['lead', 'user'])
            ->whereNull('completed_at')
            ->where('due_at', '<', $todayStart)
            ->orderBy('due_at')
            ->take(6)
            ->get();

        foreach ($overdueActivities as $activity) {
            $attentionItems->push([
                'level' => 'danger',
                'title' => $activity->title ?: 'Atividade vencida',
                'description' => ($activity->lead?->name ? 'Lead: '.$activity->lead->name.' • ' : '').'Venceu '.$activity->due_at?->diffForHumans(),
                'url' => $activity->lead ? route('crm.leads.show', $activity->lead) : route('crm.agenda'),
                'label' => 'Resolver agora',
            ]);
        }

        $staleLeads = (clone $leadQuery)
            ->whereNull('won_at')
            ->whereNull('lost_at')
            ->where(function (Builder $query) use ($staleLimit): void {
                $query->where('updated_at', '<', $staleLimit)
                    ->orWhere(function (Builder $inner) use ($staleLimit): void {
                        $inner->whereNull('last_contact_at')->where('created_at', '<', $staleLimit);
                    });
            })
            ->orderBy('updated_at')
            ->take(6)
            ->get();

        foreach ($staleLeads as $lead) {
            $days = max(1, (int) $lead->updated_at?->diffInDays(now()));
            $attentionItems->push([
                'level' => $days >= 7 ? 'danger' : 'warning',
                'title' => $lead->name.' está sem movimentação',
                'description' => 'Oportunidade parada há '.$days.' dia'.($days === 1 ? '' : 's').'.',
                'url' => route('crm.leads.show', $lead),
                'label' => 'Abrir lead',
            ]);
        }

        $attentionItems = $attentionItems->take(8)->values();

        $feed = (clone $timelineQuery)
            ->with(['lead', 'user'])
            ->latest()
            ->take(12)
            ->get();

        $leadSeries = collect(range(6, 0))->map(function (int $daysAgo) use ($leadQuery): array {
            $date = now()->subDays($daysAgo);
            return [
                'label' => $date->translatedFormat('D'),
                'date' => $date->format('Y-m-d'),
                'value' => (clone $leadQuery)->whereDate('created_at', $date)->count(),
            ];
        });

        $maxLeadSeries = max(1, (int) $leadSeries->max('value'));

        return view('dashboard.index', [
            'metrics' => $metrics,
            'isCto' => $isCto,
            'isInternal' => $isInternal,
            'activeCompany' => $activeCompany,
            'availableCompanies' => $isInternal ? Company::query()->orderByRaw('COALESCE(trade_name, name)')->get() : collect(),
            'recentLeads' => (clone $leadQuery)->with(['company', 'owner'])->latest()->take(6)->get(),
            'attentionItems' => $attentionItems,
            'feed' => $feed,
            'leadSeries' => $leadSeries,
            'maxLeadSeries' => $maxLeadSeries,
        ]);
    }
}
