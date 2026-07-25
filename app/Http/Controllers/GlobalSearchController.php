<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Domain\CRM\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->string('q'));

        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $query) . '%';
        $results = collect();

        Company::query()
            ->where(function ($builder) use ($like) {
                $builder->where('name', 'like', $like)
                    ->orWhere('trade_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('cnpj', 'like', $like);
            })
            ->latest()
            ->limit(5)
            ->get()
            ->each(function (Company $company) use ($results) {
                $results->push([
                    'id' => 'company-' . $company->id,
                    'type' => 'company',
                    'category' => 'Empresas',
                    'label' => $company->trade_name ?: $company->name,
                    'description' => $company->email ?: ($company->city ?: 'Empresa cadastrada'),
                    'initial' => 'E',
                    'url' => route('companies.show', $company),
                ]);
            });

        Lead::query()
            ->with('company:id,name,trade_name')
            ->where(function ($builder) use ($like) {
                $builder->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('campaign', 'like', $like);
            })
            ->latest()
            ->limit(5)
            ->get()
            ->each(function (Lead $lead) use ($results) {
                $companyName = $lead->company?->trade_name ?: $lead->company?->name;
                $results->push([
                    'id' => 'lead-' . $lead->id,
                    'type' => 'lead',
                    'category' => 'Leads',
                    'label' => $lead->name,
                    'description' => $companyName ?: ($lead->email ?: 'Lead do CRM'),
                    'initial' => 'L',
                    'url' => route('leads.show', $lead),
                ]);
            });

        User::query()
            ->where(function ($builder) use ($like) {
                $builder->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('position', 'like', $like);
            })
            ->latest()
            ->limit(5)
            ->get()
            ->each(function (User $user) use ($results) {
                $results->push([
                    'id' => 'user-' . $user->id,
                    'type' => 'user',
                    'category' => 'Usuários',
                    'label' => $user->name,
                    'description' => Str::limit($user->position ?: $user->email, 72),
                    'initial' => 'U',
                    'url' => route('users.show', $user),
                ]);
            });

        return response()->json([
            'results' => $results->take(15)->values(),
        ]);
    }
}
