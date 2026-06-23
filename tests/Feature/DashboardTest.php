<?php

namespace Tests\Feature;

use App\Models\Citizen;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_dashboard_loads_successfully(): void
    {
        Citizen::factory()->count(2)->create();

        $response = $this->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Registered Citizens');
    }
}
