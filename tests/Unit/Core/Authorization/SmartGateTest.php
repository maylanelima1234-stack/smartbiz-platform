<?php

namespace Tests\Unit\Core\Authorization;

use App\Core\Authorization\Services\PermissionService;
use App\Core\Authorization\Services\SmartGate;
use Illuminate\Auth\Access\AuthorizationException;
use Mockery;
use PHPUnit\Framework\TestCase;

class SmartGateTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_allows_an_authorized_permission(): void
    {
        $permissions = Mockery::mock(PermissionService::class);
        $permissions->shouldReceive('allows')
            ->once()
            ->with('crm.view')
            ->andReturnTrue();

        $gate = new SmartGate($permissions);

        $this->assertTrue($gate->allows('crm.view'));
    }

    public function test_it_denies_an_unauthorized_permission(): void
    {
        $permissions = Mockery::mock(PermissionService::class);
        $permissions->shouldReceive('denies')
            ->once()
            ->with('crm.delete')
            ->andReturnTrue();

        $gate = new SmartGate($permissions);

        $this->assertTrue($gate->denies('crm.delete'));
    }

    public function test_authorize_throws_when_access_is_denied(): void
    {
        $permissions = Mockery::mock(PermissionService::class);
        $permissions->shouldReceive('denies')
            ->once()
            ->with('users.delete')
            ->andReturnTrue();

        $gate = new SmartGate($permissions);

        $this->expectException(AuthorizationException::class);

        $gate->authorize('users.delete');
    }
}

