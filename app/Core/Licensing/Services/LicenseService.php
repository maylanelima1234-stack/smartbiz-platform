<?php

namespace App\Core\Licensing\Services;

use App\Core\Context\PlatformContext;
use App\Core\Licensing\Contracts\LicenseRepositoryContract;
use BackedEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use LogicException;

class LicenseService
{
    public function __construct(
        private readonly PlatformContext $context,
        private readonly LicenseRepositoryContract $repository,
    ) {
    }

    public function plan(): string
    {
        $company = $this->context->company();

        if ($company === null) {
            throw new LogicException(
                'Não é possível resolver a licença sem uma empresa ativa.'
            );
        }

        return Cache::remember(
            $this->cacheKey((int) $company->getKey()),
            now()->addSeconds($this->cacheTtl()),
            fn (): string => $this->repository->planFor($company)
        );
    }

    public function allows(string|BackedEnum $feature): bool
    {
        $feature = $this->normalize($feature);

        return in_array(
            $feature,
            (array) config("licensing.plans.{$this->plan()}.features", []),
            true
        );
    }

    public function denies(string|BackedEnum $feature): bool
    {
        return ! $this->allows($feature);
    }

    public function limit(string|BackedEnum $limit): ?int
    {
        $value = Arr::get(
            config("licensing.plans.{$this->plan()}.limits", []),
            $this->normalize($limit)
        );

        if ($value === null || $value === 'unlimited') {
            return null;
        }

        return (int) $value;
    }

    public function remaining(string|BackedEnum $limit, int $used): ?int
    {
        $maximum = $this->limit($limit);

        if ($maximum === null) {
            return null;
        }

        return max(0, $maximum - max(0, $used));
    }

    public function withinLimit(string|BackedEnum $limit, int $used, int $increment = 1): bool
    {
        $maximum = $this->limit($limit);

        if ($maximum === null) {
            return true;
        }

        return ($used + max(0, $increment)) <= $maximum;
    }

    public function flush(?int $companyId = null): void
    {
        $companyId ??= $this->context->companyId();

        if ($companyId !== null) {
            Cache::forget($this->cacheKey((int) $companyId));
        }
    }

    private function normalize(string|BackedEnum $value): string
    {
        return $value instanceof BackedEnum
            ? (string) $value->value
            : $value;
    }

    private function cacheKey(int $companyId): string
    {
        return "smart-license:v1:company:{$companyId}";
    }

    private function cacheTtl(): int
    {
        return max(1, (int) config('licensing.cache_ttl_seconds', 300));
    }
}
