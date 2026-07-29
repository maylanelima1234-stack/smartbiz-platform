<?php

namespace Tests\Feature;

use Tests\TestCase;

class CrmRouteIsolationTest extends TestCase
{
    public function test_legacy_lead_routes_are_not_registered(): void
    {
        $this->assertFalse(app('router')->has('leads.index'));
        $this->assertTrue(app('router')->has('crm.leads.index'));
        $this->assertTrue(app('router')->has('crm.kanban'));
    }
}
