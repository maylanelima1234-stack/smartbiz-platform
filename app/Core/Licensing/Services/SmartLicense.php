<?php

namespace App\Core\Licensing\Services;

use BackedEnum;

class SmartLicense
{
    public function __construct(
        private readonly LicenseService $licenses,
    ) {
    }

    public function plan(): string
    {
        return $this->licenses->plan();
    }

    public function allows(string|BackedEnum $feature): bool
    {
        return $this->licenses->allows($feature);
    }

    public function denies(string|BackedEnum $feature): bool
    {
        return $this->licenses->denies($feature);
    }

    public function limit(string|BackedEnum $limit): ?int
    {
        return $this->licenses->limit($limit);
    }

    public function remaining(string|BackedEnum $limit, int $used): ?int
    {
        return $this->licenses->remaining($limit, $used);
    }

    public function withinLimit(string|BackedEnum $limit, int $used, int $increment = 1): bool
    {
        return $this->licenses->withinLimit($limit, $used, $increment);
    }

    public function flush(): void
    {
        $this->licenses->flush();
    }
}
