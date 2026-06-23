<?php

namespace Tests\Feature;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Visa;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    public function test_search_page_loads(): void
    {
        $response = $this->get(route('search'));

        $response->assertOk();
        $response->assertSee('Global Search');
    }

    public function test_search_finds_citizens_visas_and_cases(): void
    {
        $citizen = Citizen::factory()->create(['passport_number' => 'TGSEARCH1']);
        Visa::factory()->create(['visa_number' => 'V-SEARCH-001', 'passport_number' => 'TGSEARCH1']);
        AssistanceCase::factory()->create([
            'citizen_id' => $citizen->id,
            'case_number' => 'CA-2026-SEARCH',
        ]);

        $response = $this->get(route('search', ['q' => 'SEARCH']));

        $response->assertOk();
        $response->assertSee('TGSEARCH1');
        $response->assertSee('V-SEARCH-001');
        $response->assertSee('CA-2026-SEARCH');
    }
}
