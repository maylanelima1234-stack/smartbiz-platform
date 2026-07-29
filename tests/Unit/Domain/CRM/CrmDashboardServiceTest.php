<?php
namespace Tests\Unit\Domain\CRM;
use App\Core\Context\PlatformContext;
use App\Domain\CRM\Services\CrmDashboardService;
use Mockery;
use PHPUnit\Framework\TestCase;
class CrmDashboardServiceTest extends TestCase
{
    protected function tearDown(): void { Mockery::close(); parent::tearDown(); }
    public function test_service_can_be_constructed(): void
    {
        $service=new CrmDashboardService(Mockery::mock(PlatformContext::class));
        $this->assertInstanceOf(CrmDashboardService::class,$service);
    }
}

