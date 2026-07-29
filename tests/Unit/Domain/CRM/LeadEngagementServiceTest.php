<?php

namespace Tests\Unit\Domain\CRM;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Services\LeadEngagementService;
use Mockery;
use PHPUnit\Framework\TestCase;

class LeadEngagementServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_service_can_be_constructed(): void
    {
        $service = new LeadEngagementService(Mockery::mock(PlatformContext::class));
        $this->assertInstanceOf(LeadEngagementService::class, $service);
    }
}
