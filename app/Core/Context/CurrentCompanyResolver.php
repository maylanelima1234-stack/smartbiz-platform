<?php

namespace App\Core\Context;

use App\Core\Context\Contracts\CurrentCompanyResolverContract;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CurrentCompanyResolver implements CurrentCompanyResolverContract
{
    private const SESSION_KEY = 'platform.active_company_id';

    public function resolve(User $user): ?Company
    {
        $activeCompanyId = session(self::SESSION_KEY);

        if ($activeCompanyId !== null && $this->canAccessCompany($user, (int) $activeCompanyId)) {
            return Company::query()->find($activeCompanyId);
        }

        $firstCompanyId = $user->hasMultiCompanyScope()
            ? Company::query()
                ->whereIn('status', ['Ativa', 'active'])
                ->orderBy('id')
                ->value('id')
            : DB::table('company_user')
                ->where('user_id', $user->getKey())
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->orderBy('id')
                ->value('company_id');

        if ($firstCompanyId === null) {
            $this->clear();

            return null;
        }

        session([self::SESSION_KEY => (int) $firstCompanyId]);

        return Company::query()->find($firstCompanyId);
    }

    public function switch(User $user, Company $company): void
    {
        if (! $this->canAccessCompany($user, (int) $company->getKey())) {
            throw ValidationException::withMessages([
                'company' => 'Você não possui acesso ativo a esta empresa.',
            ]);
        }

        session([self::SESSION_KEY => (int) $company->getKey()]);
        session()->regenerate();
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    private function canAccessCompany(User $user, int $companyId): bool
    {
        if ($user->hasMultiCompanyScope()) {
            return Company::query()->whereKey($companyId)->exists();
        }

        return DB::table('company_user')
            ->where('user_id', $user->getKey())
            ->where('company_id', $companyId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->exists();
    }
}
