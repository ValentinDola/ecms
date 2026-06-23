<?php

namespace Tests\Feature;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Visa;
use Tests\TestCase;

class PrintViewTest extends TestCase
{
    public function test_citizen_print_view_loads(): void
    {
        $citizen = Citizen::factory()->create([
            'first_name' => 'Print',
            'last_name' => 'Citizen',
        ]);

        $response = $this->get(route('print.citizen', $citizen));

        $response->assertOk();
        $response->assertSee('Print Citizen');
        $response->assertSee('Citizen Registry Record');
    }

    public function test_visa_print_view_loads(): void
    {
        $visa = Visa::factory()->create(['visa_number' => 'V-PRINT-001']);

        $response = $this->get(route('print.visa', $visa));

        $response->assertOk();
        $response->assertSee('V-PRINT-001');
        $response->assertSee('Visa Record');
    }

    public function test_assistance_case_print_view_loads(): void
    {
        $case = AssistanceCase::factory()->create(['case_number' => 'CA-2026-PRINT']);

        $response = $this->get(route('print.case', $case));

        $response->assertOk();
        $response->assertSee('CA-2026-PRINT');
        $response->assertSee('Consular Assistance Case Summary');
    }
}
