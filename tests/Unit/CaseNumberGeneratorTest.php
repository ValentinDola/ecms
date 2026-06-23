<?php

namespace Tests\Unit;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Services\CaseNumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CaseNumberGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_first_case_number_for_the_year(): void
    {
        $year = (int) now()->format('Y');

        $number = app(CaseNumberGenerator::class)->generate($year);

        $this->assertSame("CA-{$year}-00001", $number);
    }

    public function test_it_increments_sequence_based_on_existing_cases(): void
    {
        $year = (int) now()->format('Y');
        $citizen = Citizen::factory()->create();

        AssistanceCase::factory()->create([
            'citizen_id' => $citizen->id,
            'case_number' => "CA-{$year}-00007",
        ]);

        $number = app(CaseNumberGenerator::class)->generate($year);

        $this->assertSame("CA-{$year}-00008", $number);
    }
}
