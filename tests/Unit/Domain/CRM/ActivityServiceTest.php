<?php
namespace Tests\Unit\Domain\CRM;
use App\Core\Context\PlatformContext;
use App\Domain\CRM\Services\ActivityService;
use Mockery;
use PHPUnit\Framework\TestCase;
class ActivityServiceTest extends TestCase {
 protected function tearDown():void{Mockery::close();parent::tearDown();}
 public function test_service_can_be_constructed():void{$service=new ActivityService(Mockery::mock(PlatformContext::class));$this->assertInstanceOf(ActivityService::class,$service);}
}
