<?php

namespace App\Http\Controllers;

use App\Domain\CRM\Models\Lead;
use App\Models\Company;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $metrics = [
            'companies' => Company::query()->count(),
            'leads' => Lead::query()->count(),
            'pipeline_value' => 'R$ '.number_format((float) Lead::query()->sum('value'), 2, ',', '.'),
            'follow_ups' => Lead::query()
                ->whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<=', now()->addDays(7))
                ->count(),
        ];

        return view('dashboard.index', [
            'metrics' => $metrics,
            'recentLeads' => Lead::query()->with('company')->latest()->take(5)->get(),
            'recentCompanies' => Company::query()->latest()->take(5)->get(),
        ]);
    }
}
