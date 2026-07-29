<?php

namespace App\Http\Controllers;

use App\Domain\CRM\Models\Lead;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompanyWorkspaceController extends Controller
{
    public function __invoke(Company $company): View
    {
        $team = User::query()
            ->where(function ($query) use ($company): void {
                $query->where('company_id', $company->id)
                    ->orWhereExists(function ($subQuery) use ($company): void {
                        $subQuery->selectRaw('1')
                            ->from('company_user')
                            ->whereColumn('company_user.user_id', 'users.id')
                            ->where('company_user.company_id', $company->id)
                            ->where('company_user.status', 'active')
                            ->whereNull('company_user.deleted_at');
                    });
            })
            ->orderBy('name')
            ->get();

        $leadQuery = Lead::query()->where('company_id', $company->id);

        $metrics = [
            'team' => $team->count(),
            'leads' => (clone $leadQuery)->count(),
            'qualified' => (clone $leadQuery)->where('status', 'qualified')->count(),
            'follow_ups' => (clone $leadQuery)
                ->whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<=', now()->addDays(7))
                ->count(),
        ];

        $healthScore = $this->healthScore($company, $metrics);

        return view('companies.workspace', [
            'company' => $company,
            'team' => $team,
            'metrics' => $metrics,
            'healthScore' => $healthScore,
            'recentLeads' => (clone $leadQuery)->latest()->take(6)->get(),
        ]);
    }

    private function healthScore(Company $company, array $metrics): int
    {
        $score = 20;

        $score += $company->status === 'Ativa' || $company->status === 'active' ? 20 : 5;
        $score += $metrics['team'] > 0 ? 15 : 0;
        $score += $metrics['leads'] > 0 ? 20 : 0;
        $score += $metrics['qualified'] > 0 ? 15 : 0;
        $score += filled($company->email) && filled($company->phone) ? 10 : 0;

        return min(100, $score);
    }
}
