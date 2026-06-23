<?php

namespace Tests\Unit;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Visa;
use App\Services\GlobalSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    private GlobalSearchService $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchService = app(GlobalSearchService::class);
    }

    public function test_empty_query_returns_no_results(): void
    {
        Citizen::factory()->create();

        $results = $this->searchService->search('');

        $this->assertSame('', $results['query']);
        $this->assertSame(0, $results['total']);
        $this->assertTrue($results['citizens']->isEmpty());
        $this->assertTrue($results['visas']->isEmpty());
        $this->assertTrue($results['cases']->isEmpty());
    }

    public function test_it_finds_citizens_by_passport_number(): void
    {
        $citizen = Citizen::factory()->create(['passport_number' => 'TG9999999']);

        $results = $this->searchService->search('TG9999999');

        $this->assertSame(1, $results['total']);
        $this->assertTrue($results['citizens']->contains('id', $citizen->id));
    }

    public function test_it_finds_visas_by_visa_number(): void
    {
        $visa = Visa::factory()->create(['visa_number' => 'V-2026-99999']);

        $results = $this->searchService->search('V-2026-99999');

        $this->assertSame(1, $results['total']);
        $this->assertTrue($results['visas']->contains('id', $visa->id));
    }

    public function test_it_finds_assistance_cases_by_case_number(): void
    {
        $case = AssistanceCase::factory()->create(['case_number' => 'CA-2026-55555']);

        $results = $this->searchService->search('CA-2026-55555');

        $this->assertSame(1, $results['total']);
        $this->assertTrue($results['cases']->contains('id', $case->id));
    }
}
