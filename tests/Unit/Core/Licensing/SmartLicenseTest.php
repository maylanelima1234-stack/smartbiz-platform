<?php

namespace Tests\Unit\Core\Licensing;

use App\Core\Licensing\Services\LicenseService;
use App\Core\Licensing\Services\SmartLicense;
use Mockery;
use PHPUnit\Framework\TestCase;

class SmartLicenseTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_allows_an_enabled_feature(): void
    {
        $service = Mockery::mock(LicenseService::class);
        $service->shouldReceive('allows')
            ->once()
            ->with('crm')
            ->andReturnTrue();

        $license = new SmartLicense($service);

        $this->assertTrue($license->allows('crm'));
    }

    public function test_it_returns_a_limit(): void
    {
        $service = Mockery::mock(LicenseService::class);
        $service->shouldReceive('limit')
            ->once()
            ->with('users')
            ->andReturn(5);

        $license = new SmartLicense($service);

        $this->assertSame(5, $license->limit('users'));
    }

    public function test_it_checks_remaining_capacity(): void
    {
        $service = Mockery::mock(LicenseService::class);
        $service->shouldReceive('remaining')
            ->once()
            ->with('users', 3)
            ->andReturn(2);

        $license = new SmartLicense($service);

        $this->assertSame(2, $license->remaining('users', 3));
    }
}
