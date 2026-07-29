<?php

namespace Tests\Unit\Domain\CRM;

use App\Core\Context\PlatformContext;
use App\Domain\CRM\Services\LeadService;
use Mockery;
use PHPUnit\Framework\TestCase;

class LeadServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_service_can_be_constructed(): void
    {
        $context = Mockery::mock(PlatformContext::class);
        $service = new LeadService($context);

        $this->assertInstanceOf(LeadService::class, $service);
    }
}

