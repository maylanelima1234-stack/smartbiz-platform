<?php

namespace Tests\Unit\Core\Audit;

use App\Core\Audit\DTO\AuditEntry;
use App\Core\Audit\Services\AuditService;
use App\Core\Audit\Services\SmartAudit;
use App\Models\SmartAuditLog;
use Mockery;
use PHPUnit\Framework\TestCase;

class SmartAuditTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_records_an_audit_entry(): void
    {
        $entry = new AuditEntry(event: 'crm.lead.created');
        $log = new SmartAuditLog();

        $service = Mockery::mock(AuditService::class);
        $service->shouldReceive('record')
            ->once()
            ->with($entry)
            ->andReturn($log);

        $audit = new SmartAudit($service);

        $this->assertSame($log, $audit->record($entry));
    }

    public function test_it_logs_a_business_event(): void
    {
        $log = new SmartAuditLog();

        $service = Mockery::mock(AuditService::class);
        $service->shouldReceive('log')
            ->once()
            ->with(
                'user.logged_in',
                null,
                [],
                [],
                ['channel' => 'web']
            )
            ->andReturn($log);

        $audit = new SmartAudit($service);

        $this->assertSame(
            $log,
            $audit->log(
                event: 'user.logged_in',
                metadata: ['channel' => 'web']
            )
        );
    }
}
